FROM php:7.4-fpm

RUN apt-get update \
    && apt-get -yqq install --no-install-recommends \
      default-mysql-client \
      libpng-dev \
    && apt-get autoremove -y --purge \
    && rm -fr /var/lib/apt/lists/*

RUN docker-php-ext-install gd opcache pdo pdo_mysql

COPY . /app
COPY tools/docker/php/php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /app

RUN chown -R www-data: /app
