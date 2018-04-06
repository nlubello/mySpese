FROM php:7.2-apache

MAINTAINER Niccolò Lubello

COPY . /srv/app
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN apt-get update && \
    apt-get install -y --no-install-recommends git zip

RUN curl --silent --show-error https://getcomposer.org/installer | php

# Install laravel system requirements
RUN apt-get update && apt-get install -y \
        libmcrypt-dev \
        # deps for pdo_mysql
        mysql-client \
    # no deps
    && docker-php-ext-install tokenizer \
                              mbstring \
                              pdo \
    # has deps
    && docker-php-ext-install pdo_mysql

RUN chown -R www-data:www-data /srv/app \
    && a2enmod rewrite
