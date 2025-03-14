FROM adminer:4.8.1

COPY ./html /var/www/html/

COPY --chown=adminer:adminer /plugins /var/www/html/plugins
COPY --chown=adminer:adminer /plugins-enabled /var/www/html/plugins-enabled

ENV LOGIN_SERVERS='{}'
ENV PHP_CLI_SERVER_WORKERS=5

RUN if [ -z "$PROTECTED_ACCESS" ]; then \
        export PROTECTED_ACCESS='default:$apr1$eULvW9bt$G6ckX02ZzX9b4fXQAK8.0/'; \
    fi && \
    echo "AuthType Basic" > /var/www/html/.htaccess && \
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

# Expose ports used by Nginx and Adminer
EXPOSE 80 8880

# Use a script to start both Nginx and PHP-Adminer in parallel
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Start Nginx and Adminer
CMD ["/start.sh"]
