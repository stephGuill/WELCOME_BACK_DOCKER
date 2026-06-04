# Job 02 : Déployer le jeu Mario dans un conteneur Docker

## Objectif
Déployer et exécuter le jeu Mario dans un conteneur Docker, accessible via un navigateur web.

## Aperçu du jeu Mario

![Super Mario HTML5 dans Docker](./mario_screenshot.png)

---

## Étapes réalisées

### 1. Recherche d'une image Mario sur Docker Hub
```
docker search mario
```

### 2. Téléchargement de l'image Docker du jeu Mario
```
docker pull sevenajay/mario:latest
```

### 3. Vérification de la présence de l'image
```
docker images
```

### 4. Lancement du conteneur avec publication du port 4545
```
docker run -d -p 4545:80 sevenajay/mario:latest
```
- `-d` : mode détaché (en arrière-plan)
- `-p 4545:80` : mappe le port 4545 de l'hôte vers le port 80 du conteneur

### 5. Vérification que le conteneur tourne
```
docker ps
```

### 6. Accès au jeu Mario
Ouvre ton navigateur et va à l'adresse :
```
http://localhost:4545
```

### 7. Arrêt du conteneur
Pour arrêter le conteneur, récupère son ID avec `docker ps` puis :
```
docker stop <ID_du_conteneur>
```
Exemple :
```
docker stop c42347cc061b
```

---

## Explications complémentaires
- Un conteneur Docker n'est pas une machine virtuelle complète, il partage le noyau de l'OS hôte.
- Une image Docker contient tout le nécessaire pour exécuter une application (dépendances, fichiers, etc.).
- Docker-compose permet de gérer plusieurs conteneurs à la fois (à voir plus tard).

Pour plus d'informations : [https://docs.docker.com/get-started/](https://docs.docker.com/get-started/)
