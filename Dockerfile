FROM php:8.2-apache
RUN apt-get update && apt-get install -y git unzip libonig-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql
