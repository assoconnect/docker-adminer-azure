FROM adminer:5

COPY ./html /var/www/html/

ENV LOGIN_SERVERS='{}'
ENV PHP_CLI_SERVER_WORKERS=5

# Expose ports used by Adminer
EXPOSE 80

CMD	[ "php", "-S", "[::]:80", "-t", "/var/www/html" ]
