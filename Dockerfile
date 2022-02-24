FROM php:7.4-apache
RUN apt-get update && apt-get upgrade -y
RUN docker-php-ext-install pdo pdo_mysql

COPY $PWD/ /var/www/html

EXPOSE 80

# start Apache2 on image start
CMD ["/usr/sbin/apache2ctl","-DFOREGROUND"]