FROM adminer:latest

COPY ./html /var/www/html/

ENV LOGIN_SERVERS = '{}'
ENV PHP_CLI_SERVER_WORKERS = 5
