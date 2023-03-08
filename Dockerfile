FROM php:8-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
     git \
	 curl \
	 zip \
	 unzip \
	 libgmp-dev
	 

RUN a2enmod rewrite && a2enmod ssl

RUN docker-php-ext-install pdo pdo_mysql gmp



# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

