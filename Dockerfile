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
# Importing database
#
#   docker exec -i zf3apigilityoauth2_db_1 mysql -h localhost -u zf3 -pzf3 zf3_apigility < vendor/zfcampus/zf-oauth2/data/db_oauth2.sql
# 

FROM ubuntu:xenial
MAINTAINER Dolly Aswin <dolly.aswin@aqilix.com>

COPY docker/apache2/zf3.vhost.conf /etc/apache2/sites-available/
COPY docker/apache2/apache2-foreground /usr/local/bin

RUN apt-get update \
    && apt-get install -y wget curl git apache2 libapache2-mod-php7.0 \
       php7.0 php7.0-intl php7.0-curl php7.0-json php7.0-mbstring \
       php7.0-mcrypt php7.0-mysql php7.0-xml \
    && mv /var/www/html /var/www/public \
    && curl -sS https://getcomposer.org/installer \
     | php -- --install-dir=/usr/local/bin --filename=composer
RUN a2dissite 000-default \
    && a2enmod rewrite \
    && a2ensite zf3.vhost

WORKDIR /var/www
EXPOSE 80
CMD ["apache2-foreground"]
