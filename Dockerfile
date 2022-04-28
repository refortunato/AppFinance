FROM php:8.1-apache
RUN docker-php-ext-install pdo pdo_mysql
# BAIXA O COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /var/www
# COPY . /var/www/
RUN rm -rf /var/www/html
# RUN ln -s ./public ./html
