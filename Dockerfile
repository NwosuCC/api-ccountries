#FROM httpd
#
#COPY . /var/www/html/

FROM php:7.2-apache

## E.g: Given the below snippet from a docker-compose file with the arguments:
## EITHER written in Object notation (buildno: 1), e.g:
#    build:
#      context: .
#      args:
#        buildno: 1
#        gitcommithash: cdc3b19
#
## OR with the arguments written as key=value pair (- buildno=1), e.g:
#    build:
#      context: .
#      args:
#        - buildno=1
#        - gitcommithash=cdc3b19
#
## NOTE: Any ARG declared above the 'FROM' instruction (above) will not be available in the build
## You can use the 'ARG' instruction to pull in the arguments defined in the docker-compose file
# ARG buildno
# ARG gitcommithash
#
## Then, apply the imported ARGs in this Dockerfile file using php-style variables
# RUN echo "Build number: $buildno"
# RUN echo "Based on commit: $gitcommithash"

RUN apt-get update \
 && apt-get install -y git zlib1g-dev default-mysql-client libzip-dev \
 && docker-php-ext-install zip mysqli pdo pdo_mysql \
# && pecl install xdebug \
# && docker-php-ext-install enable xdebug \
# && echo 'xdebug.remote_enable=on' >> /usr/local/etc/php/conf.d/xdebug.ini \
# && echo 'xdebug.remote_host=host.docker.internal' >> /usr/local/etc/php/conf.d/xdebug.ini \
 && a2enmod rewrite \
 && sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
 && echo "AllowEncodedSlashes On" >> /etc/apache2/apache2.conf

#RUN mv /var/www/html /var/www/public \
#RUN cp -rf /var/www/html /var/www
#RUN mv /var/www/public /var/www/html

WORKDIR /var/www/html

