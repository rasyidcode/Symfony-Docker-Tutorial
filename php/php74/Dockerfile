FROM php:7.4.33-fpm

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions

RUN install-php-extensions opcache intl zip pdo_mysql pdo_pgsql

RUN apt-get -y update && \
    apt-get -y install git unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash

RUN mv ~/.symfony5/bin/symfony /usr/local/bin/symfony

WORKDIR /var/www

USER $user