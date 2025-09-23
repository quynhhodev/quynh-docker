# #syntax=docker/dockerfile-upstream:1

# ARG PHP_VERSION="8.3"

# ARG PHP_EXTENSIONS="intl gd apcu amqp pdo_pgsql redis igbinary"


# FROM thecodingmachine/php:${PHP_VERSION}-v4-slim-apache


# ENV APACHE_RUN_USER=docker \
# APACHE_RUN_GROUP=docker

# COPY docker/apache/etc/apache2/conf-available/docker-php.conf /etc/apache2/conf-available/docker-php.conf

# RUN chown docker:docker /var/www/html

# EXPOSE 80
# WORKDIR /var/www/html

FROM php:8.2-cli

ENV COMPOSER_ALLOW_SUPERUSER="1"

EXPOSE 80

RUN apt-get update && apt-get install -y libmemcached-dev libssl-dev zlib1g-dev \
	&& pecl install memcached-3.2.0 \
	&& docker-php-ext-enable memcached

RUN composer install --no-interaction --no-scripts --no-progress --no-dev; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer run-script post-install-cmd --no-dev; \
	composer dump-env prod; \
	composer clear-cache; \
    sync;

