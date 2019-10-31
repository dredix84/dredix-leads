FROM phpdockerio/php73-fpm:latest
WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install php7.3-sqlite php-memcached php7.3-mysql php-xdebug php7.3-imap php7.3-intl php-mongodb php-yaml \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install MongoDB
RUN apt update \
    && apt-get install libcurl4-openssl-dev pkg-config libssl-dev -y \
    && apt-get install php-pear -y \
    && apt install php-dev -y

RUN pecl install mongodb
