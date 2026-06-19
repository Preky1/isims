FROM php:8.2-fpm

RUN docker-php-ext-install pdo pdo_mysql \
    && apt-get update && apt-get install -y nginx \
    && rm -rf /var/lib/apt/lists/*

RUN echo 'server {\n\
    listen 80;\n\
    root /var/www/html/public;\n\
    index index.php;\n\
    location / { try_files $uri $uri/ /index.php?$query_string; }\n\
    location ~ \\.php$ {\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n\
        include fastcgi_params;\n\
    }\n\
}' > /etc/nginx/sites-available/default

COPY . /var/www/html/

RUN mkdir -p /var/www/html/public/assets/uploads \
             /var/www/html/public/assets/img \
             /var/www/html/storage/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/public/assets/uploads \
    && chmod +x /var/www/html/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
