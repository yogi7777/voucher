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

# Klone das Repository und kopiere den Quellcode in das Apache-Verzeichnis
RUN git clone --depth=1 https://github.com/yogi7777/voucher.git /repo \
    && cp -r /repo/src/* /var/www/html/ \
    && chown -R www-data:www-data /var/www/html \
    && rm -rf /repo

# Skript hinzufügen, das Umgebungsvariablen setzt
RUN echo '#!/bin/bash' > /usr/local/bin/docker-entrypoint.sh && \
    echo 'echo "SetEnv DB_USER \"${DB_USER}\"" > /etc/apache2/conf-available/environment.conf' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'echo "SetEnv DB_PASSWORD \"${DB_PASSWORD}\"" >> /etc/apache2/conf-available/environment.conf' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'exec "$@"' >> /usr/local/bin/docker-entrypoint.sh && \
    chmod +x /usr/local/bin/docker-entrypoint.sh

# Docker Entrypoint: Setze die Umgebungsvariablen und starte Apache
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# Apache-Standardbefehl
CMD ["apache2-foreground"]