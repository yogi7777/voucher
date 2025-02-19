#!/bin/bash
echo "SetEnv DB_HOST \"${MARIADB_HOST}\"" > /etc/apache2/conf-available/environment.conf
echo "SetEnv DB_NAME \"${MARIADB_DATABASE}\"" >> /etc/apache2/conf-available/environment.conf
echo "SetEnv DB_USER \"${MARIADB_USER}\"" >> /etc/apache2/conf-available/environment.conf
echo "SetEnv DB_PASS \"${MARIADB_PASSWORD}\"" >> /etc/apache2/conf-available/environment.conf
a2enconf environment

apache2-foreground
