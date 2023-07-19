FROM adminer:latest

COPY CombinedRoot.crt.pem /var/www/html/plugins-enabled/
COPY login-ssl.php /var/www/html/plugins-enabled/
COPY adminer.css /var/www/html/
