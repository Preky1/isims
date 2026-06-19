FROM php:8.2-apache

ARG CACHEBUST=1

RUN docker-php-ext-install pdo pdo_mysql

RUN find /etc/apache2/mods-enabled -name 'mpm_*' -delete \
    && ln -s /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf \
    && ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load \
    && a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri 's|/var/www/html|${APACHE_DOCUMENT_ROOT}|g' \
        /etc/apache2/sites-available/000-default.conf \
        /etc/apache2/apache2.conf \
    && sed -ri 's|AllowOverride None|AllowOverride All|g' \
        /etc/apache2/apache2.conf

COPY . /var/www/html/

RUN mkdir -p /var/www/html/public/assets/uploads \
             /var/www/html/public/assets/img \
             /var/www/html/storage/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/public/assets/uploads \
    && chmod +x /var/www/html/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
