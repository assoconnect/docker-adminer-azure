FROM adminer:latest

COPY ./html /var/www/html/

ENV LOGIN_SERVERS = '{}'
