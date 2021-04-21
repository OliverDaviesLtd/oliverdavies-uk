FROM php:7.4-fpm-buster AS base

RUN apt update -yqq && apt install -yqq \
    libpng-dev \
    mariadb-client \
    zlib1g-dev \
  && docker-php-ext-install \
    exif \
    gd \
    pdo_mysql

###

FROM base AS dev

ARG xdebug_version=2.9.0

RUN apt update -yqq \
  && apt install -yqq \
    git \
    pv \
    vim \
    zip \
  && pecl install xdebug-${xdebug_version} \
  && docker-php-ext-enable xdebug

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
ENV PATH="/app/bin:$PATH"

COPY composer.json composer.lock /app/
COPY assets /app/assets
COPY tools/patches /app/tools/patches
RUN composer install

COPY tools/docker/images/php/configs/php.ini /usr/local/etc/php/conf.d/php.ini
