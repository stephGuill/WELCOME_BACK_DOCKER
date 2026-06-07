# Image de base officielle PHP 8.2 avec Apache intégré
# Apache est pré-configuré pour servir des fichiers depuis /var/www/html
FROM php:8.2-apache

# Copie notre fichier PHP dans le dossier web d'Apache
# /var/www/html est le DocumentRoot par défaut d'Apache dans ce conteneur
COPY index.php /var/www/html/index.php

# Informe Docker que le conteneur écoute sur le port 80 (HTTP)
# Ce port sera mappé vers un port hôte via -p lors du docker run
EXPOSE 80
