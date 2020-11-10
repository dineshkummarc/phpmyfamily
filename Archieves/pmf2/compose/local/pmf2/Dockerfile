FROM php:7.3-apache

COPY ./compose/local/pmf2/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY ./inc/database.inc.docker ./inc/database.inc.php
WORKDIR /app
RUN apt-get update
RUN apt-get install -y apt-utils
RUN apt-get upgrade -y
RUN a2enmod rewrite
#RUN apt-get install -y graphviz php-pear
#RUN pear install Image_GraphViz
RUN apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install pdo pdo_mysql mysqli bcmath gd

RUN mkdir /docs
RUN chown -R www-data:www-data /docs
RUN mkdir /images
RUN chown -R www-data:www-data /images
