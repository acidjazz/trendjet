FROM centos:latest
RUN \
	yum -y update && \
	yum -y install deltarpm && \
	yum -y install epel-release && \
	rpm --import http://rpms.famillecollet.com/RPM-GPG-KEY-remi && \
	rpm -ivh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm && \
	yum-config-manager --enable extras && \
	yum-config-manager --enable epel && \
	yum-config-manager --enable remi && \
	yum-config-manager --enable remi-php71
RUN yum -y install nginx git php php-cli php-mbstring php-fpm php-pdo php-mysqlnd python python-pip  php-xml php-xmlrpc php-process
RUN pip install awscli
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"
