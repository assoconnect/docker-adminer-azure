FROM adminer:5.4.0

COPY ./html /var/www/html/

ENV LOGIN_SERVERS='{}'
ENV PHP_CLI_SERVER_WORKERS=5
ENV PROTECTED_ACCESS='default:$apr1$eULvW9bt$G6ckX02ZzX9b4fXQAK8.0/'

# Install Nginx
USER root
RUN apk add nginx

# Configure Nginx as a reverse proxy for Adminer
RUN rm /etc/nginx/http.d/default.conf
COPY etc/nginx/adminer.conf /etc/nginx/http.d/adminer.conf

# Expose ports used by Nginx and Adminer
EXPOSE 80 8880

# Use a script to start both Nginx and PHP-Adminer in parallel
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Start Nginx and Adminer
CMD ["/start.sh"]
