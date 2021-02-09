FROM php:7.4-fpm-buster AS base

RUN apt update -yqq && apt install -yqq \
    git \
    libpng-dev \
    mariadb-client \
    zip \
    zlib1g-dev \
  && docker-php-ext-install \
    exif \
    gd \
    pdo_mysql

###

FROM base AS dev

WORKDIR /app

ENV PATH="./bin:./vendor/bin:$PATH"

RUN apt update -yqq \
  && apt install -yqq \
    pv \
    vim \
  && pecl install xdebug-2.9.0 \
  && docker-php-ext-enable xdebug

COPY --from=composer:1 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock /app/
COPY assets /app/assets
RUN composer install

COPY tools/docker/images/php/configs/php.ini /usr/local/etc/php/conf.d/php.ini
