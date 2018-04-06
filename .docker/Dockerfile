FROM php:7.2-apache

MAINTAINER Paul Redmond

COPY . /srv/app
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN apt-get update && \
    apt-get install -y --no-install-recommends git zip

RUN curl --silent --show-error https://getcomposer.org/installer | php

RUN chown -R www-data:www-data /srv/app \
    && a2enmod rewrite
