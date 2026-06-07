# Projet 2 - Welcome to Docker - Part 1

## Objectif
Maîtriser les commandes Docker de base : pull, run, ps, stop, rm, et comprendre la gestion des conteneurs et images.

---

## Commandes de base testées

### 1) Vérifier la version de Docker
```bash
docker --version
```
**Résultat :**
```
Docker version 29.3.1, build c2be9cc
```

---

### 2) Afficher les informations Docker
```bash
docker info
```
**Résultat (extrait) :**
- Conteneurs : 2 (1 en cours d'exécution, 1 arrêté)
- Images : 3
- Version serveur : 29.3.1
- Kernel : 5.15.167.4-microsoft-standard-WSL2

---

### 3) Lister les conteneurs actifs
```bash
docker ps
```
**Résultat :**
```
CONTAINER ID   IMAGE                      COMMAND                  CREATED       STATUS             PORTS
08530d219764   docker/welcome-to-docker   "/docker-entrypoint.…"   11 seconds    Up 9 seconds       0.0.0.0:3000->80/tcp
c42347cc061b   sevenajay/mario:latest     "/docker-entrypoint.…"   About 1 hour  Up About 1 hour    0.0.0.0:4545->80/tcp
```

---

### 4) Lister toutes les images Docker
```bash
docker images
```
**Résultat :**
```
IMAGE                      ID             DISK USAGE   CONTENT SIZE
hello-world:latest         452a468a4bf9   25.9kB       9.49kB
nginx:latest               7150b3a39203   240MB        65.8MB
sevenajay/mario:latest     8541a39162f3   325MB        94.8MB
```

---

### 5) Récupérer l'image Docker
```bash
docker pull docker/welcome-to-docker
```
**Résultat :**
```
latest: Pulling from docker/welcome-to-docker
...
Digest: sha256:c4d56c24da4f009ecf8352146b43497fe78953edb4c679b841732beb97e588b0
Status: Downloaded newer image for docker/welcome-to-docker:latest
```

---

### 6) Lancer un conteneur
```bash
docker run -d --rm -p 3000:80 docker/welcome-to-docker
```
**Options utilisées :**
- `-d` : mode détaché (en arrière-plan)
- `--rm` : supprime automatiquement le conteneur à l'arrêt
- `-p 3000:80` : mappe le port 3000 de l'hôte vers le port 80 du conteneur

**Résultat (ID du conteneur) :**
```
08530d219764feab32f8f4e48c1534ac2767e2b8bf958b09f18bb0684e53d2ce
```

**Accès au conteneur :**
- URL : http://127.0.0.1:3000
- ou http://localhost:3000

**Résultat en navigateur :**

![Welcome to Docker - Success](./images/welcome-to-docker-success.png)

*Le conteneur est bien actif et accessible via le navigateur. L'interface affiche "Congratulations!!! You ran your first container."*

---

## Commandes pour arrêter et supprimer

### Arrêter un conteneur
```bash
docker stop <CONTAINER_ID>
```
Exemple :
```bash
docker stop 08530d219764
```

### Supprimer un conteneur
```bash
docker rm <CONTAINER_ID>
```
Exemple :
```bash
docker rm 08530d219764
```

### Supprimer une image
```bash
docker rmi <IMAGE_ID>
```
Exemple :
```bash
docker rmi 7150b3a39203
```

---

## Cas d'usage avancés

### Supprimer un conteneur spécifique (forcé)
```bash
docker rm -f <CONTAINER_ID>
```

### Supprimer plusieurs conteneurs
```bash
docker rm <CONTAINER_ID_1> <CONTAINER_ID_2> <CONTAINER_ID_3>
```

### Supprimer tous les conteneurs arrêtés
```bash
docker container prune
```

### Supprimer une image spécifique
```bash
docker rmi <IMAGE_ID>
```

### Supprimer une image spécifique (forcé)
```bash
docker rmi -f <IMAGE_ID>
```

### Supprimer plusieurs images
```bash
docker rmi <IMAGE_ID_1> <IMAGE_ID_2>
```

### Supprimer toutes les images inutilisées
```bash
docker image prune -a
```

### Nettoyer l'ensemble du système Docker
```bash
docker system prune -a
```
Cela supprime :
- Tous les conteneurs arrêtés
- Tous les réseaux non utilisés
- Toutes les images inutilisées
- Tous les caches de construction

---

## Résumé des compétences acquises
✅ Installation et vérification de Docker  
✅ Récupération d'images depuis Docker Hub  
✅ Lancement et gestion de conteneurs  
✅ Utilisation des ports (`-p`)  
✅ Mode détaché et suppression automatique  
✅ Commandes de base (`ps`, `images`, `info`)  
✅ Arrêt et suppression de conteneurs  
✅ Nettoyage des ressources Docker  
