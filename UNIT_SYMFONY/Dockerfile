# Image de base PHP 8.2 avec PHP-FPM (FastCGI Process Manager)
# PHP-FPM est utilisé pour traiter les requêtes PHP transmises par Nginx via FastCGI
FROM php:8.2-fpm

# Mise à jour de apt et installation des dépendances système nécessaires à Symfony :
# - git : pour Composer (téléchargement des paquets)
# - unzip : extraction des archives dépendant de zip
# - libicu-dev : bibliothèque Unicode pour l'extension intl (internationalisation)
# - libzip-dev / zip : support des archives ZIP pour Composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    zip \
    # Installation des extensions PHP requises par Symfony :
    # pdo_mysql : connexion MySQL via PDO (Doctrine)
    # intl     : internationalisation (traductions, formats dates)
    # zip      : gestion des archives ZIP
    && docker-php-ext-install pdo_mysql intl zip \
    # Nettoyage pour réduire la taille de l'image finale
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copie de l'exécutable Composer depuis son image officielle
# Multi-stage copy : évite d'installer Composer via curl (plus propre et sûr)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Répertoire de travail = racine de l'application Symfony dans le conteneur
WORKDIR /var/www/html

# Commande par défaut : démarre PHP-FPM en avant-plan (Nginx lui envoie les requêtes PHP)
CMD ["php-fpm"]
