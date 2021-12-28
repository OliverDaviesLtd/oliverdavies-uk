FROM node:14-buster-slim AS assets

ARG NODE_ENV=production
ENV NODE_ENV="${NODE_ENV:-production}" \
  PATH="${PATH}:/node_modules/.bin"

WORKDIR /app

RUN mkdir /node_modules \
  && chown node:node -R /node_modules /app

USER node

COPY --chown=node:node run .

WORKDIR /app/web/themes/custom/opdavies

COPY --chown=node:node web/themes/custom/opdavies/.yarnrc .
COPY --chown=node:node web/themes/custom/opdavies/package.json .
COPY --chown=node:node web/themes/custom/opdavies/yarn.lock .

RUN yarn install && yarn cache clean

COPY --chown=node:node config ../../../../config

CMD ["bash"]

###

FROM nginx:1-alpine AS nginx
COPY tools/docker/images/nginx/configs/vhost.conf /etc/nginx/conf.d/default.conf
WORKDIR /app
COPY web web/
WORKDIR /app/web/themes/custom/opdavies
COPY --from=assets /app/web/themes/custom/opdavies/build build

###

FROM php:7.4-fpm-buster AS base

RUN apt-get update -yqq && \
  apt-get install -yqq --no-install-recommends \
    libpng-dev \
    mariadb-client \
    unzip \
    zlib1g-dev \
  && docker-php-ext-install \
    exif \
    gd \
    pdo_mysql \
  && rm -fr /var/lib/apt/lists/*

###

FROM base AS dev

ARG xdebug_version=2.9.0

RUN apt-get update -yqq \
  && apt-get install -yqq --no-install-recommends \
    git \
    pv \
    vim \
    zip \
  && pecl install xdebug-${xdebug_version} \
  && docker-php-ext-enable xdebug \
  && rm -fr /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY tools/docker/images/php/configs/php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /app
ENV PATH="$PATH:/app/bin"

COPY composer.json composer.lock /app/
COPY assets /app/assets
COPY tools/patches /app/tools/patches
RUN composer install
