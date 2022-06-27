FROM php:8.0-fpm-alpine

RUN apk add autoconf automake make gcc g++ libtool pkgconfig

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer