# Job 04 : Exercice pratique Docker (Nginx)

## Objectif
Démarrer un serveur Nginx dans un conteneur Docker, vérifier son exécution, puis arrêter et supprimer le conteneur.

## 1) Lancer Nginx
Commande :
```bash
docker run -d -p 8080:80 nginx
```

Résultat :
```bash
9961fc08b3033cc6f6da0cdfc276aaf60622420882a7f55879706a2a792192c2
```

Accès web attendu :
- http://127.0.0.1:8080

---

## 2) Vérifier les conteneurs en cours d’exécution
Commande :
```bash
docker ps
```

Sortie observée (pendant le test) :
```bash
CONTAINER ID   IMAGE                    COMMAND                  CREATED          STATUS          PORTS                                      NAMES
9961fc08b303   nginx                    "/docker-entrypoint.…"   10 seconds ago   Up 8 seconds    0.0.0.0:8080->80/tcp, [::]:8080->80/tcp   heuristic_saha
c42347cc061b   sevenajay/mario:latest   "/docker-entrypoint.…"   36 minutes ago   Up 36 minutes   0.0.0.0:4545->80/tcp, [::]:4545->80/tcp   brave_elgamal
```

---

## 3) Entrer/exécuter une commande dans le conteneur
Exemple de vérification du dossier web Nginx :
```bash
docker exec 9961fc08b303 ls /usr/share/nginx/html
```

Sortie :
```bash
50x.html
index.html
```

---

## 4) Arrêter puis supprimer le conteneur
Arrêt :
```bash
docker stop 9961fc08b303
```

Suppression :
```bash
docker rm 9961fc08b303
```

---

## 5) Vérifications globales
Conteneurs actifs :
```bash
docker ps
```

Images locales :
```bash
docker images -a
```

Sortie images observée :
```bash
IMAGE                    ID             DISK USAGE   CONTENT SIZE   EXTRA
hello-world:latest       452a468a4bf9   25.9kB       9.49kB         U
nginx:latest             7150b3a39203   240MB        65.8MB
sevenajay/mario:latest   8541a39162f3   325MB        94.8MB         U
```

---

## Rendu demandé (rappel)
- Exécution d’un conteneur Docker ✅
- Déploiement du jeu Mario accessible via le navigateur ✅ (Job 02, http://localhost:4545)
- Utilisation des commandes Docker de base (`run`, `ps`, `stop`) ✅
