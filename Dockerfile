#use an official PHP runtime as parent image
FROM php:8.2-apache

#install required system packages and dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

#set the working directory in the container
WORKDIR /var/www/html
#copy the currect directory contents into the container at /var/www/html
COPY . /var/www/html

#install any dependencies your PHP app may need
# if we have specific PHP extensions required, install them here
#(for example, this project is going thru postgres)
#adding postgres support
RUN docker-php-ext-install pdo_pgsql

#copy custom apache configuration
COPY apache.conf /etc/apache2/sites-available/000-default.conf

#enable apache modules
#a2enmod is a utility that assists with managing apache modules
RUN a2enmod rewrite

#set apache to bing to ip address 0.0.0.0
#RUN echo "Listen 0.0.0.0:80">>/etc/apache2/apache2.conf
#supposed to uncomment out but video says he did not uncomment out
# and it still worked when deployed. 

#set environment variables here if needed, though Prof Gray recommends
#not providing here and not pushing to github

#expose port 80 to allow incoming connections to the container
EXPOSE 8080

#by default apache is started automatically. you can change or customize
#the startup command if necessary. 
#CMD ["apache2-foreground"]
