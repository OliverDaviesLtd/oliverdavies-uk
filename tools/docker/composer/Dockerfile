FROM php:7.2-cli

COPY tools/docker/composer/composer-installer.sh /usr/local/bin/composer-installer

RUN apt-get -yqq update \
  && apt-get -yqq install --no-install-recommends \
    git \
    libpng-dev \
    unzip \
    wget \
    zip \
  && apt-get autoremove -y --purge \
  && rm -fr /var/lib/apt/lists/* \
  && chmod +x /usr/local/bin/composer-installer \
  && composer-installer \
  && mv composer.phar /usr/local/bin/composer \
  && chmod +x /usr/local/bin/composer \
  && composer --version

RUN docker-php-ext-install gd

COPY . /app

WORKDIR /app

ENTRYPOINT [ "/usr/local/bin/composer" ]
