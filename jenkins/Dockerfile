FROM jenkins/jenkins:2.426.3-jdk17
USER root
RUN apt-get update && apt-get install -y lsb-release
RUN curl -fsSLo /usr/share/keyrings/docker-archive-keyring.asc \
  https://download.docker.com/linux/debian/gpg
RUN echo "deb [arch=$(dpkg --print-architecture) \
  signed-by=/usr/share/keyrings/docker-archive-keyring.asc] \
  https://download.docker.com/linux/debian \
  $(lsb_release -cs) stable" > /etc/apt/sources.list.d/docker.list
RUN apt-get update && apt-get install -y docker-ce-cli
USER jenkins
RUN jenkins-plugin-cli --plugins "blueocean docker-workflow"

#FROM jenkins/jenkins:lts
#USER root
#
## Install Docker CLI only, not the daemon
#RUN curl -fsSL https://get.docker.com -o get-docker.sh && sh get-docker.sh
#
## Install Docker Compose
#RUN curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose && \
#    chmod +x /usr/local/bin/docker-compose
#
## Cleanup
#RUN rm get-docker.sh
#
## Switch back to the jenkins user
#USER jenkins

#FROM jenkins/jenkins:lts
#USER root
#RUN curl -fsSL https://get.docker.com -o get-docker.sh && sh get-docker.sh
#RUN curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
#RUN groupadd -g 1001 docker || groupmod -g 1001 docker
#RUN usermod -aG docker jenkins
#RUN usermod -aG root jenkins
#RUN chmod +x /usr/local/bin/docker-compose
#RUN rm get-docker.sh
#USER jenkins