# Image de base Node.js 16 en version alpine (ultra-légère)
FROM node:16-alpine

# Définir le répertoire de travail dans le conteneur
WORKDIR /app

# Copier les fichiers de dépendances en premier (cache Docker optim)
COPY package*.json ./
# Installer toutes les dépendances (production + dev si présentes)
RUN npm install

# Copier le code source du serveur
COPY server.js ./

# Déclarer le port sur lequel l'API Express écoute
EXPOSE 3000

# Commande de démarrage de l'API
CMD ["node", "server.js"]
