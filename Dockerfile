FROM composer:2 AS composer

FROM php:8.3-apache-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        curl unzip libicu-dev libonig-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j2 intl mbstring mysqli gd opcache \
    && a2enmod rewrite headers setenvif \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts --no-autoloader
COPY . .
RUN composer dump-autoload --no-dev --optimize --no-scripts \
    && mkdir -p writable/cache writable/logs writable/session writable/uploads public/uploads \
    && find . -type d -exec chmod 755 {} + \
    && find . -type f -exec chmod 644 {} + \
    && chown root:root . \
    && chown -R www-data:www-data writable public/uploads

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/portfolio.ini
COPY --chmod=755 docker/entrypoint.sh /usr/local/bin/portfolio-entrypoint

ENTRYPOINT ["portfolio-entrypoint"]
CMD ["apache2-foreground"]
