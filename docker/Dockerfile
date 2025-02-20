FROM php:8.2-apache

# Systemabhängigkeiten installieren
RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    zip \
    unzip \
    gettext-base \
    && docker-php-ext-install zip pdo pdo_mysql

# Apache-Konfiguration aktivieren
RUN a2enmod rewrite
RUN a2enmod env

# Arbeitsverzeichnis setzen
WORKDIR /var/www/html

# Erstelle benötigte Ordner und setze Berechtigungen
RUN chown -R www-data:www-data /var/www/html

# Docker Entrypoint: Setze die Umgebungsvariablen und starte Apache
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# Apache-Standardbefehl
CMD ["apache2-foreground"]