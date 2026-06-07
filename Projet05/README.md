# Projet05 - Docker Apache / PHP

## Objectif
Créer une image Docker basée sur Apache + PHP, afficher les informations serveur avec `phpinfo()`, lancer le conteneur sur le port local 8080, puis l'arrêter.

---

## 1) Fichier PHP

Fichier créé : [index.php](index.php)

Contenu :
```php
<?php phpinfo();
```

---

## 2) Dockerfile

Fichier créé : [Dockerfile](Dockerfile)

Contenu :
```dockerfile
FROM php:8.2-apache

COPY index.php /var/www/html/index.php

EXPOSE 80
```

Explication :
- `php:8.2-apache` est une image officielle Apache + PHP
- le fichier `index.php` est copié dans le dossier web d’Apache
- le conteneur expose le port `80`

---

## 3) Construire l’image Docker

Commande :
```bash
docker build -t projet05-phpinfo .
```

Résultat observé : build terminé avec succès.

---

## 4) Lancer le conteneur

Commande :
```bash
docker run -d -p 8080:80 --name projet05-apache projet05-phpinfo
```

Résultat :
- conteneur créé : `5d500007496b...`
- mapping de ports : `8080 -> 80`

---

## 5) Vérifier que le conteneur tourne

Commande :
```bash
docker ps
```

Extrait :
```text
CONTAINER ID   IMAGE             STATUS         PORTS                    NAMES
5d500007496b   projet05-phpinfo  Up ...         0.0.0.0:8080->80/tcp    projet05-apache
```

---

## 6) Accéder à phpinfo()

URL :
- http://127.0.0.1:8080
- http://localhost:8080

Vous devez voir la page `PHP Version` avec toutes les informations serveur.

Captures :

![phpinfo page 1](./images/Capture%20d'%C3%A9cran%20phpinfo1.png)

![phpinfo page 2](./images/Capture%20d'%C3%A9cran%20phpinfo2.png)

![phpinfo page 3](./images/Capture%20d'%C3%A9cran%20phpinfo3.png)

![phpinfo page 4](./images/Capture%20d'%C3%A9cran%20phpinfo4.png)

![phpinfo page 5](./images/Capture%20d'%C3%A9cran%20phpinfo5.png)

![phpinfo page 6](./images/Capture%20d'%C3%A9cran%20phpinfo6.png)

![phpinfo page 7](./images/Capture%20d'%C3%A9cran%20phpinfo7.png)

![phpinfo page 8](./images/Capture%20d'%C3%A9cran%20phpinfo8.png)

---

## 7) Arrêter le conteneur

Commande :
```bash
docker stop projet05-apache
```

Vérification :
```bash
docker ps -a --filter name=projet05-apache
```

Extrait :
```text
STATUS
Exited (0)
```

Preuve (sortie terminal) :
```text
CONTAINER ID   IMAGE              COMMAND                  CREATED              STATUS                      PORTS     NAMES
5d500007496b   projet05-phpinfo   "docker-php-entrypoi…"   About a minute ago   Exited (0) 26 seconds ago             projet05-apache
```

Note : capture non fournie pour cette étape.

---

## Compétence validée
- Savoir créer et exploiter une image Docker Apache/PHP
