FROM php:8.0-fpm-alpine

# package install
RUN set -eux && \
    apk update && \
    apk add --no-cache \
    autoconf \
    gcc \
    g++ \
    git \
    icu-dev \
    libzip-dev \
    make \
    oniguruma-dev \
    unzip  && \
    docker-php-ext-install intl pdo_mysql zip bcmath

# composer
COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

# copy setting & sources file
COPY ./docker/app/php.ini /usr/local/etc/php/php.ini
COPY ./docker/app/zzz-docker.conf /usr/local/etc/php-fpm.d/zzz-docker.conf
COPY . /var/www/html

# setup composer & laravel
RUN composer install

# unix socket
RUN mkdir /var/run/php-fpm
VOLUME ["/var/run/php-fpm"]
