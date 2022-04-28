FROM php:8.1-apache
# COPY ./api-mini-bank /var/www
WORKDIR /var/www
RUN docker-php-ext-install pdo pdo_mysql