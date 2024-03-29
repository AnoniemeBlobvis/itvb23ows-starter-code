FROM composer:2.7.2 AS composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader
RUN composer dump-autoload -o

FROM php:8.0-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
COPY --from=composer /var/www/html/vendor /var/www/html/vendor
COPY application/src /var/www/html/
COPY .env /var/www/html/
#COPY ../../vendor /var/www/html/vendor