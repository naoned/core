FROM php:8.1-cli

RUN pecl install xdebug-3.1.0 \
    && docker-php-ext-enable xdebug

COPY php.ini /usr/local/etc/php/conf.d
