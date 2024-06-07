FROM adminer:latest

COPY init_container.sh ./
COPY ./html /var/www/html/

ENV LOGIN_SERVERS = '{}'
ENV PHP_CLI_SERVER_WORKERS = 5

# Set up SSH
RUN apt-get update \
    && apt-get install -y --no-install-recommends dialog \
    && apt-get install -y --no-install-recommends openssh-server \
    && echo "root:Docker!" | chpasswd \
    && chmod u+x ./init_container.sh
COPY conf/sshd_config /etc/ssh/
EXPOSE 8000 2222

ENTRYPOINT [ "./init_container.sh" ]
