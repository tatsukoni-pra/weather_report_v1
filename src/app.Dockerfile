FROM php:8.0-fpm-alpine

# composer
COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

# package install
RUN set -eux && \
    apk update && \
    apk add --update --no-cache \
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

# copy setting & sources file
COPY docker/app/php.ini /usr/local/etc/php/php.ini
COPY docker/app/zzz-docker.conf /usr/local/etc/php-fpm.d/zzz-docker.conf
COPY ./ /var/www

# unix socket
RUN mkdir /var/run/php-fpm
VOLUME ["/var/run/php-fpm"]
