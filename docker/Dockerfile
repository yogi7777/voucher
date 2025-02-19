# Dockerfile
FROM php:8.2-apache

# System Dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP Extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql

# Apache Config
RUN a2enmod rewrite env

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Skript hinzufügen, das Umgebungsvariablen setzt und Repository klont
RUN echo '#!/bin/bash' > /usr/local/bin/docker-entrypoint.sh && \
    echo 'echo "SetEnv DB_HOST \"${DB_HOST}\"" > /etc/apache2/conf-available/environment.conf' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'echo "SetEnv DB_NAME \"${DB_NAME}\"" >> /etc/apache2/conf-available/environment.conf' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'echo "SetEnv DB_USER \"${DB_USER}\"" >> /etc/apache2/conf-available/environment.conf' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'echo "SetEnv DB_PASS \"${DB_PASS}\"" >> /etc/apache2/conf-available/environment.conf' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'a2enconf environment' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'if [ -z "$(ls -A /var/www/html)" ]; then' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '    git clone https://github.com/YOUR_USERNAME/voucher-system.git .' >> /usr/local/bin/docker-entrypoint.sh && \
    echo '    chown -R www-data:www-data /var/www/html' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'fi' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'apache2-foreground' >> /usr/local/bin/docker-entrypoint.sh && \
    chmod +x /usr/local/bin/docker-entrypoint.sh

# Set permissions
RUN chown -R www-data:www-data /var/www/html

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]