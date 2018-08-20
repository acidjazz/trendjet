#!/bin/bash

aws iam attach-role-policy --role-name api --policy-arn arn:aws:iam::aws:policy/service-role/AWSConfigRole

echo -e "" > /home/ec2-user/.ssh/config
echo -e "Host *\n\tStrictHostKeyChecking no" >> /home/ec2-user/.ssh/config

aws s3 cp s3://trendjet-vault/keys/trendjet /home/ec2-user/.ssh/id_rsa

chmod 0700 /home/ec2-user/.ssh/id_rsa
chmod 0700 /home/ec2-user/.ssh/config

chown -R ec2-user:ec2-user /home/ec2-user/.ssh/

yum -y update
yum -y install git

su ec2-user -c "
curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.11/install.sh | bash
chmod a+x ~/.nvm/nvm.sh
~/.nvm/nvm.sh
source ~/.bashrc
nvm install 9.7.1
curl -o- -L https://yarnpkg.com/install.sh | bash
source ~/.bashrc
git clone git@github.com:acidjazz/trendjet.git
cd trendjet/web
sudo iptables -t nat -A PREROUTING -p tcp --dport 80 -j REDIRECT --to-ports 3000
aws s3 cp s3://trendjet-vault/envs/staging-web .env
yarn global add pm2
yarn 
yarn generate
yarn pm2start
"
