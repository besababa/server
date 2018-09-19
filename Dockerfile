FROM php:7.2.9-apache

ADD . /var/www/
WORKDIR /var/www/
ADD apache/000-default.conf /etc/apache2/sites-enabled/000-default.conf

RUN apt-get update &&\
    apt-get install -y git zip&&\
    rm -rf /var/lib/apt/lists/* &&\
    curl -sS https://getcomposer.org/installer |\
        php -- --install-dir=/usr/local/bin --filename=composer  &&\
    composer install &&\
    a2enmod rewrite &&\
    chown www-data: /var/www -R

