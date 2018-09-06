FROM php:7-fpm

ADD . /var/www/
WORKDIR /var/www

RUN apt-get update &&\
    apt-get install -y git zip&&\
    rm -rf /var/lib/apt/lists/* &&\
    curl https://getcomposer.org/installer > install-composer.php &&\
    php install-composer.php --install-dir=/usr/local/bin --filename=composer &&\
    rm  install-composer.php &&\
    composer install &&\
    chown www-data: /var/www -R

