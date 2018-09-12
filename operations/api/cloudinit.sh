#!/bin/bash

aws iam attach-role-policy --role-name api --policy-arn arn:aws:iam::aws:policy/service-role/AWSConfigRole

echo -e "" > /home/ec2-user/.ssh/config
echo -e "Host *\n\tStrictHostKeyChecking no" >> /home/ec2-user/.ssh/config

aws s3 cp s3://trendjet-vault/keys/trendjet /home/ec2-user/.ssh/id_rsa

chmod 0700 /home/ec2-user/.ssh/id_rsa
chmod 0700 /home/ec2-user/.ssh/config

chown -R ec2-user:ec2-user /home/ec2-user/.ssh

yum -y update
amazon-linux-extras install nginx1.12 php7.2
yum -y install git php-pear php-devel gcc ImageMagick ImageMagick-devel php-mbstring php-bcmath php-pecl-zip
pecl install imagick
echo "extension=imagick.so" > /etc/php.d/30-imagick.ini

service php-fpm restart

echo '
user  nginx;
worker_processes  4;
pid        /var/run/nginx.pid;
events {
  worker_connections  1024;
}
http {
  include       /etc/nginx/mime.types;
  default_type  application/octet-stream;
  access_log  /var/log/nginx/access.log;
  error_log /var/log/nginx/error.log;
  sendfile        on;
  keepalive_timeout  65;
  gzip on;
  gzip_disable "msie6";
  server {
    listen 81;
    return 301 https://$host$request_uri;
  }
  server {
    listen       80;
    index   index.php;
    server_name  localhost;
    client_max_body_size 1000m;
    client_body_timeout 180s;
    root         /home/ec2-user/trendjet/api/public/;
    location / {
      if (!-e $request_filename) {
        rewrite ^(.*)$ /index.php;
      }
    }
    location ~ \.php$ {
      fastcgi_split_path_info ^(.+\.php)(/.+)$;
      fastcgi_pass unix:/var/run/php-fpm/www.sock;
      
      fastcgi_index index.php;
      include fastcgi_params;
      fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
  }
}
' > /etc/nginx/nginx.conf

echo '
[www]
user = ec2-user
group = ec2-user
listen = /run/php-fpm/www.sock
listen.acl_users = apache,nginx,ec2-user
listen.allowed_clients = 127.0.0.1

pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
slowlog = /var/log/php-fpm/www-slow.log
php_admin_value[error_log] = /var/log/php-fpm/www-error.log
php_admin_flag[log_errors] = on
php_value[session.save_handler] = files
php_value[session.save_path]    = /var/lib/php/session
php_value[soap.wsdl_cache_dir]  = /var/lib/php/wsdlcache
' > /etc/php-fpm.d/www.conf

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"

# setup the actual code and secrets

su ec2-user -c "
cd /home/ec2-user
git clone git@github.com:acidjazz/trendjet.git
cd /home/ec2-user/trendjet/api
aws s3 cp s3://trendjet-vault/envs/api-staging .env
/usr/local/bin/composer install
chmod -R 777 /home/ec2-user/trendjet/api/storage/
"
service php-fpm restart
service nginx restart

## TODO
# FFMPEG and imagemagick
# https://www.johnvansickle.com/ffmpeg/
# get the 3.4 release and tar xf 
# https://gist.github.com/jmsaavedra/62bbcd20d40bcddf27ac

# yum install ImageMagick
