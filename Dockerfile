FROM wordpress:6.9.4-apache

# Installation de Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configuration optimisée de Xdebug pour Docker/Windows
RUN { \
    echo 'xdebug.mode=debug'; \
    echo 'xdebug.start_with_request=yes'; \
    echo 'xdebug.client_host=host.docker.internal'; \
    echo 'xdebug.client_port=9003'; \
    echo 'xdebug.log=/tmp/xdebug.log'; \
} > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# On s'assure que les droits sont corrects pour Apache
RUN chown -R www-data:www-data /var/www/html

# Installation de Node.js et NPM (via le dépôt Nodesource)
# RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
#    && apt-get install -y nodejs

# Installation globale de l'outil de création de blocs Wordpress (optionnel mais pratique)
# RUN npm install -g @wordpress/create-block

WORKDIR /var/www/html
