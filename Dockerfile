FROM php:7.2-apache
COPY . /var/www/html
COPY config/php.ini /usr/local/etc/php/
COPY config/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
EXPOSE 80