FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libssl-dev \
    unzip \
    git \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && curl -sS https://getcomposer.org | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite
