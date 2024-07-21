FROM php:8.2-apache

# Activer l'extension PDO MySQL
RUN docker-php-ext-install pdo_mysql

# Installer Xdebug
RUN pecl install xdebug

# Copier le fichier de configuration Xdebug dans le conteneur
COPY xdebug.ini /usr/local/etc/php/conf.d/

# Activer le module Xdebug dans la configuration PHP
RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini

# Copier le fichier de configuration personnalisé de PHP
COPY custom-php.ini /usr/local/etc/php/conf.d/custom-php.ini

# Copier la configuration Apache personnalisée et l'inclure
COPY custom-apache.conf /etc/apache2/conf-available/custom-apache.conf
RUN a2enconf custom-apache