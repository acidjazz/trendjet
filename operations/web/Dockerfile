FROM circleci/python:latest-node-browsers
# RUN sudo apt-get install apt-transport-https ca-certificates
# RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
# RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list
# RUN curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
RUN sudo apt-get update && sudo apt-get install yarn groff
RUN sudo pip3 install awscli
