FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libicu-dev \
        libonig-dev \
        libxml2-dev \
    && docker-php-ext-install \
        bcmath \
        intl \
        mbstring \
        pdo_mysql \
        xml \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" \
        /etc/apache2/sites-available/*.conf \
        /etc/apache2/apache2.conf \
        /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

COPY . .
COPY docker/entrypoint.sh /usr/local/bin/mudifinance-entrypoint

RUN chmod +x /usr/local/bin/mudifinance-entrypoint \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/testing storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache public \
    && chmod -R ug+rwx storage bootstrap/cache

ENTRYPOINT ["mudifinance-entrypoint"]
CMD ["apache2-foreground"]
