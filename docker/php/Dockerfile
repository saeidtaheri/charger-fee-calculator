FROM php:8.3-fpm

WORKDIR /srv/www

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    libzip-dev

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG uid
RUN useradd -G www-data,root -u $uid -d /home/lms lms
RUN mkdir -p /home/lms/.composer && \
    chown -R lms:lms /home/lms
