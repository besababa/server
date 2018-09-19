FROM php:7.2.9-apache

RUN apt-get update &&\
    apt-get install -y git zip&&\
    rm -rf /var/lib/apt/lists/* &&\
    docker-php-ext-install pdo pdo_mysql &&\
    a2enmod rewrite &&\
    curl -sS https://getcomposer.org/installer |\
        php -- --install-dir=/usr/local/bin --filename=composer

ADD . /var/www/
WORKDIR /var/www/
ADD apache/000-default.conf /etc/apache2/sites-enabled/000-default.conf


RUN composer install &&\
    chown www-data: /var/www -R

