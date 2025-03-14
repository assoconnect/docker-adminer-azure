#!/bin/sh

# Start Nginx in the background
nginx &

# Start Adminer on a custom port
php -S 127.0.0.1:8880 -t /var/www/html/
