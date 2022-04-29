FROM php:8.1.1-fpm
RUN apt-get update && apt-get install git -y
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /var/www

# FROM php:8.1-apache
# RUN docker-php-ext-install pdo pdo_mysql
# # BAIXA O COMPOSER
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# WORKDIR /var/www
# RUN rm -rf /var/www/html

