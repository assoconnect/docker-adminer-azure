#!/bin/sh

# Setup the basic auth
echo "AuthType Basic" > /var/www/html/.htaccess &&
echo "AuthName \"Restricted Access\"" >> /var/www/html/.htaccess &&
echo "AuthUserFile /var/www/html/.htpasswd" >> /var/www/html/.htaccess &&
echo "Require valid-user" >> /var/www/html/.htaccess &&
echo "$PROTECTED_ACCESS" > /var/www/html/.htpasswd

# Start Nginx in the background
nginx &

# Start Adminer on a custom port
php -S 127.0.0.1:8880 -t /var/www/html/
