FROM php:8.2-apache

# Install PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Disable conflicting MPM modules by removing them from mods-enabled
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load && \
    rm -f /etc/apache2/mods-enabled/mpm_*.conf

# Enable only mpm_prefork
RUN a2enmod mpm_prefork

# Enable mod_rewrite
RUN a2enmod rewrite

# Point document root at /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri 's|/var/www/html|${APACHE_DOCUMENT_ROOT}|g' \
        /etc/apache2/sites-available/000-default.conf \
        /etc/apache2/apache2.conf \
    && sed -ri 's|AllowOverride None|AllowOverride All|g' \
        /etc/apache2/apache2.conf

# Copy project
COPY . /var/www/html/

# Permissions
RUN mkdir -p /var/www/html/public/assets/uploads \
             /var/www/html/public/assets/img \
             /var/www/html/storage/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/public/assets/uploads \
    && chmod -R 755 /var/www/html/public/assets/img \
    && chmod +x /var/www/html/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
