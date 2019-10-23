#FROM phpdockerio/php73-fpm:latest
FROM php:7.3-apache
WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt install -y nano software-properties-common zlib1g-dev libicu-dev g++

RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-enable intl

RUN docker-php-ext-configure mbstring \
    && docker-php-ext-install mbstring \
    && docker-php-ext-enable mbstring

RUN docker-php-ext-configure mysqli \
    && docker-php-ext-install mysqli

RUN docker-php-ext-configure pdo_mysql  \
    && docker-php-ext-install pdo_mysql

RUN apt update
RUN apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev

RUN pecl install mongodb
RUN docker-php-ext-enable mongodb
#RUN echo "extension=mongodb.so" > /etc/php/7.3/fpm/conf.d/10-mongodb.ini

#RUN docker-php-ext-configure mongodb \
#    && docker-php-ext-install xdebug \
#    && docker-php-ext-enable xdebug

RUN a2enmod rewrite && service apache2 restart

RUN apt-get update \
    && apt-get -y --no-install-recommends install php7.3-sqlite php-memcached php7.3-mysql php-xdebug php7.3-imap php7.3-intl php-mongodb php-yaml \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
