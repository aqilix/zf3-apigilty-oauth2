#
# Use this dockerfile to run ZF3, Apigility with OAuth2.
#
# Start the server using docker-compose:
#
#   docker-compose up -d
#
# You can install dependencies via the container:
#
#   docker-compose run api composer install
#
# You can manipulate dev mode from the container:
#
#   docker-compose run api composer development-enable
#   docker-compose run api composer development-disable
#   docker-compose run api composer development-status
#

FROM ubuntu:xenial
MAINTAINER Dolly Aswin <dolly.aswin@aqilix.com>

COPY docker/apache2/zf3.vhost.conf /etc/apache2/sites-available/
COPY docker/apache2/apache2-foreground /usr/local/bin
COPY docker/php/apache2/conf.d/20-xdebug.ini /etc/php/7.0/apache2/conf.d/

RUN apt-get update \
    && apt-get install -y wget curl git vim apache2 libapache2-mod-php7.0 \
       php7.0 php7.0-intl php7.0-curl php7.0-json php7.0-mbstring \
       php7.0-mcrypt php7.0-mysql php7.0-xml php7.0-zip php-xdebug \
    && mv /var/www/html /var/www/public \
    && curl -sS https://getcomposer.org/installer \
     | php -- --install-dir=/usr/local/bin --filename=composer
RUN a2dissite 000-default \
    && a2enmod rewrite \
    && a2ensite zf3.vhost

WORKDIR /var/www
EXPOSE 80
CMD ["apache2-foreground"]
