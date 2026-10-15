FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libssl-dev \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN a2enmod rewrite
