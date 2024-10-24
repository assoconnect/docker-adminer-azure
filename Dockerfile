FROM adminer:latest

COPY ./html /var/www/html/

COPY --chown=adminer:adminer /plugins /var/www/html/plugins
COPY --chown=adminer:adminer /plugins-enabled /var/www/html/plugins-enabled

ENV LOGIN_SERVERS = '{}'
ENV PHP_CLI_SERVER_WORKERS = 5
