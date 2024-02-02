FROM jenkins/jenkins:lts
USER root
#RUN curl -sSL https://get.docker.com/ | sh
RUN curl -fsSL https://get.docker.com -o get-docker.sh && sh get-docker.sh

RUN rm get-docker.sh

#ARG DOCKER_GID=999
#
#RUN groupadd -g ${DOCKER_GID} docker && \
#    usermod -aG docker jenkins

USER jenkins