FROM php:7.2-apache
RUN a2enmod rewrite
RUN service apache2 restart
COPY . /var/www/html
RUN rm -rf /var/www/html/.git
RUN ls -l -A /var/www/html
RUN chown -R www-data:www-data /var/www/*
