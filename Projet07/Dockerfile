# Image de base Debian Bookworm en version slim (légère)
FROM debian:bookworm-slim

# Mise à jour des paquets, puis installation de Nginx, PHP-FPM, PHP-CLI et certificats CA
# DEBIAN_FRONTEND=noninteractive évite les invites interactives pendant l'install
# --no-install-recommends réduit la taille de l'image en n'installant pas les paquets optionnels
# Nettoyage du cache apt à la fin pour alléger l'image
RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
       nginx php-fpm php-cli ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Remplace la configuration Nginx par défaut par notre fichier custom (PHP-FPM activé)
COPY default.conf /etc/nginx/sites-available/default
# Copie la page principale du jeu Tic Tac Toe dans le dossier web de Nginx
COPY index.html /usr/share/nginx/html/index.html
# Copie le script PHP qui enregistre les résultats en JSON
COPY save.php /usr/share/nginx/html/save.php
# Copie le fichier JSON initial (tableau vide) pour stocker les résultats
COPY results.json /usr/share/nginx/html/results.json

# Donne à www-data (utilisateur Apache/Nginx) les droits en lecture/écriture sur le dossier web
# Indispensable pour que PHP puisse écrire dans results.json
RUN chown -R www-data:www-data /usr/share/nginx/html

# Le conteneur expose le port HTTP standard
EXPOSE 80

# Lance PHP-FPM en démon (-D) puis Nginx en avant-plan ('daemon off')
# PHP-FPM est nécessaire pour exécuter save.php via FastCGI
# Nginx doit rester en avant-plan pour que le conteneur reste actif
CMD ["sh", "-c", "php-fpm8.2 -D; nginx -g 'daemon off;'"]
