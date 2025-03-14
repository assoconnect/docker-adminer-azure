FROM adminer:latest

COPY ./html /var/www/html/

COPY --chown=adminer:adminer /plugins /var/www/html/plugins
COPY --chown=adminer:adminer /plugins-enabled /var/www/html/plugins-enabled

ENV LOGIN_SERVERS='{}'
ENV PHP_CLI_SERVER_WORKERS=5

ENV PROTECTED_ACCESS=''

RUN echo "AuthType Basic" > /var/www/html/.htaccess && \
    echo "AuthName \"Restricted Access\"" >> /var/www/html/.htaccess && \
    echo "AuthUserFile /var/www/html/.htpasswd" >> /var/www/html/.htaccess && \
    echo "Require valid-user" >> /var/www/html/.htaccess && \
    touch /var/www/html/.htpasswd && \
    echo "$PROTECTED_ACCESS" >> /var/www/html/.htpasswd

# Install Nginx
USER root
RUN apt-get update && apt-get install -y nginx && rm -rf /var/lib/apt/lists/*

# Configure Nginx as a reverse proxy for Adminer
RUN rm /etc/nginx/sites-enabled/default
COPY etc/nginx/adminer.conf /etc/nginx/conf.d/adminer.conf

# Replace Admin default CMD by starting Nginx and Adminer with another port
CMD service nginx start && php -S 127.0.0.1:8880 -t /var/www/html/
