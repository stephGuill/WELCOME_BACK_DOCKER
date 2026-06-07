# Projet06 - Industrial Apocalypse Docker

## Objectif
Créer et lancer une image Docker personnalisée avec un thème industriel/apocalyptique, accessible sur http://localhost:3000, avec des fonctions intégrées.

## Fichiers
- Dockerfile
- server.js
- package.json
- .dockerignore

## Build de l'image
```bash
docker build -t apocalypse-industrial:1.0 .
```

## Lancer le conteneur
```bash
docker run -d --name projet06-apocalypse -p 3000:3000 apocalypse-industrial:1.0
```

## Vérifier l'exécution
```bash
docker ps --filter name=projet06-apocalypse
```

Puis ouvrir :
- http://localhost:3000

## Mapping de port
- `-p 3000:3000`
- Port local = `3000`
- Port du conteneur = `3000`

## Fonctions intégrées
- Tableau de bord industriel/apocalyptique (UI custom)
- Bouton **Lancer diagnostic** (appel API santé)
- Bouton **Nouvelle mission** (mission aléatoire)
- Bouton **Mode urgence** (effet visuel dynamique)
- Compte à rebours de maintenance
- Console de logs en temps réel

## API disponibles
- `GET /api/health` : état du service, uptime, mémoire, timestamp, port
- `GET /api/mission` : renvoie une mission aléatoire

## Arrêt / suppression
```bash
docker stop projet06-apocalypse
docker rm projet06-apocalypse
```

## Captures (optionnel)
Dépose tes captures dans `images/` puis ajoute-les ici, par exemple :

```markdown
![Dashboard apocalypse](./images/dashboard-apocalypse.png)
```
