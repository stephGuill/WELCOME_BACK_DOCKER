# Image de base Node.js 20 en version slim (légère, sans outils inutiles)
FROM node:20-slim

# Sécurité : créer un groupe système "nodejs" (GID 1001) et un utilisateur système "nextjs" (UID 1001)
# Cela évite de faire tourner l'application en tant que root (bonne pratique de sécurité)
RUN addgroup --system --gid 1001 nodejs \
    && adduser --system --uid 1001 nextjs

# Définir /app comme répertoire de travail dans le conteneur
WORKDIR /app

# Copier uniquement le fichier des dépendances en premier (optimise le cache Docker)
COPY package.json ./
# Installer uniquement les dépendances de production (--omit=dev exclut les devDependencies)
RUN npm install --omit=dev

# Variable d'environnement pour Node.js : mode production (désactive les logs debug)
ENV NODE_ENV=production

# Copier le reste du code source dans /app
COPY . .

# Basculer vers l'utilisateur non-privilégié créé plus haut
USER nextjs

# Déclarer le port sur lequel le serveur Express écou tera
EXPOSE 3000

# Commande de démarrage : lance le serveur Express directement avec Node
CMD ["node", "server.js"]
