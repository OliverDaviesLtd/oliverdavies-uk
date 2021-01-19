FROM php:7.4-fpm AS base

RUN apt update -yqq && apt install -yqq zlib1g-dev libpng-dev mariadb-client git zip

RUN docker-php-ext-install exif gd pdo_mysql

###

FROM base AS dev

WORKDIR /app

RUN apt update -yqq && apt install -yqq vim nodejs npm pv
COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY ./assets/ /app/assets/
COPY ./composer.json ./composer.lock /app/
RUN composer install

COPY . /app
