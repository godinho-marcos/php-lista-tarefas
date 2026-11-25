FROM php:8.4-apache-trixie

RUN docker-php-ext-install pdo_mysql

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY app/ /var/www/html/app/
COPY public/ /var/www/html/public/
