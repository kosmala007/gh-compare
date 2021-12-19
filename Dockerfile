FROM php:8.0-apache

RUN apt-get update
RUN apt-get install -y --no-install-recommends curl

RUN curl -L 'https://getcomposer.org/installer' -o /tmp/composer-setup.php \
    && php /tmp/composer-setup.php -- --install-dir=/usr/local/bin --filename=composer \
    && rm /tmp/composer-setup.php
RUN mkdir /var/www/.composer
RUN chown www-data /var/www/.composer
RUN chmod -R 775 /var/www/.composer

COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite
RUN a2enmod headers

COPY . /var/www/html/

RUN composer install -o -a
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
