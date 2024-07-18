# Utilisation de l'image PHP officielle avec Apache
FROM php:7.1-apache

# Définition du répertoire de travail dans le conteneur
WORKDIR /var/www/html

# Copie de tous les fichiers du projet dans le répertoire de travail du conteneur
COPY ./php-test/ /var/www/html

# Modification des droits sur le répertoire de travail
RUN chown -R www-data:www-data /var/www/html

# Installation des extensions PHP nécessaires pour Symfony
RUN apt-get update \
    && apt-get install -y \
        libicu-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
    && docker-php-ext-install \
        intl \
        zip \      
        pdo_mysql \
        opcache \
        xml \
        mbstring \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && a2enmod rewrite

# Installation de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installation de PHPUnit
RUN curl -sSL https://phar.phpunit.de/phpunit.phar -o /usr/local/bin/phpunit \
    && chmod +x /usr/local/bin/phpunit

# Configuration d'Apache pour écouter sur le port 8081
RUN sed -i 's/80/8081/' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Exécution de la commande composer install
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Exposition du port 8081 pour accéder à l'application depuis l'extérieur du conteneur
EXPOSE 8081

# Commande à exécuter lorsque le conteneur démarre
CMD ["apache2-foreground"]
