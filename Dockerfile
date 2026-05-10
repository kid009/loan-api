#load base image php 8.3.x
FROM php:8.3.27-fpm

#set working directory
WORKDIR /var/www

#install extension bcmath , pdo_mysql
RUN docker-php-ext-install bcmath pdo_mysql

#update image and install git zip upzip package
RUN apt-get update
RUN apt-get install -y git zip unzip

#install nodeJS
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash -
RUN apt-get install -y nodejs

#install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

EXPOSE 9000