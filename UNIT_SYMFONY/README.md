# PROJET 01 - Symfony / Docker (UNIT_SYMFONY)

## Etat du projet
- Base Symfony 7.2 installee dans le dossier app
- Stack multi-conteneurs operationnelle
- Pages disponibles : accueil, login admin, login user
- Persistance base de donnees active (volume MySQL)

## Verification des prerequis
### Docker et Docker Compose
```powershell
docker --version
docker compose version
```

### Composer
```powershell
composer --version
```
Si Composer n'est pas installe en local, commande alternative :
```powershell
docker run --rm composer:2 --version
```

### Symfony CLI
```powershell
symfony -V
```
Si Symfony CLI n'est pas installe en local, commande alternative dans le conteneur :
```powershell
docker compose exec -T symfony_app php bin/console about
```

## ETAPE A - Explications des fichiers Docker

## 1) Role des services dans docker-compose.yml
Fichier : [docker-compose.yml](docker-compose.yml)

- symfony_app : conteneur PHP-FPM qui execute Symfony
- symfony_nginx : serveur web Nginx expose sur localhost:8080
- symfony_db : base MySQL 8 (port interne 3306, port hote 3307)
- symfony_phpmyadmin : interface web MySQL sur localhost:8081
- symfony_adminer : interface web admin BDD sur localhost:8082

Principales directives :
- build : construit l'image a partir du Dockerfile
- image : utilise une image Docker prete
- container_name : nom explicite du conteneur
- ports : publication des ports hote:conteneur
- volumes : partage de fichiers ou persistance des donnees
- depends_on : ordre de demarrage logique
- environment : variables d'environnement du service
- networks : reseau Docker commun entre conteneurs

Note port MySQL :
- Le port hote 3306 est deja utilise par un mysqld local.
- Le projet utilise donc 3307:3306 pour eviter le conflit.
- En interne Docker, Symfony parle bien a Symfony_db:3306.

## 2) Explication ligne par ligne de nginx/default.conf
Fichier : [nginx/default.conf](nginx/default.conf)

- server { ... } : declaration du vhost Nginx
- listen 80; : Nginx ecoute sur le port 80 du conteneur
- server_name localhost; : nom du serveur
- root /var/www/html/public; : dossier public Symfony
- index index.php index.html; : fichiers d'entree
- location / { try_files $uri /index.php$is_args$args; } : route toutes les requetes vers front controller Symfony
- location ~ ^/index\.php(/|$) { ... } : envoi des scripts PHP vers PHP-FPM
- fastcgi_pass Symfony_app:9000; : socket TCP vers le service PHP
- fastcgi_param SCRIPT_FILENAME ... : chemin script execute
- fastcgi_param DOCUMENT_ROOT ... : racine document
- internal; : bloc interne, non expose directement
- location ~ \.php$ { return 404; } : bloque l'execution PHP directe hors index.php
- error_log ... : log erreurs
- access_log ... : log acces

## 3) Explication ligne par ligne du Dockerfile
Fichier : [Dockerfile](Dockerfile)

- FROM php:8.2-fpm : image de base PHP-FPM
- RUN apt-get update ... : installation dependances systeme
- git/unzip/zip : outils utilitaires et installation paquets
- libicu-dev/libzip-dev : librairies requises par extensions PHP
- docker-php-ext-install pdo_mysql intl zip : extensions PHP necessaires a Symfony
- apt-get clean + rm -rf /var/lib/apt/lists/* : reduction taille image
- COPY --from=composer:2 /usr/bin/composer /usr/bin/composer : copie Composer depuis image officielle
- WORKDIR /var/www/html : dossier de travail du conteneur
- CMD ["php-fpm"] : processus principal du conteneur

## 4) Installation Symfony dans app
Commande utilisee :
```powershell
docker run --rm -v ${PWD}:/workspace -w /workspace composer:2 create-project symfony/skeleton:"7.2.*" app
```
Puis installation des packs Symfony webapp/ORM/security dans app via Composer en conteneur.

## 5) Configuration .env (base + secret)
Fichier : [app/.env](app/.env)

Variables actives :
- APP_ENV=dev
- APP_SECRET="..."
- DATABASE_URL="mysql://Symfony:Symfony@Symfony_db:3306/Symfony?serverVersion=8.0&charset=utf8mb4"

Generation d'une cle de securite forte (exemple) :
```powershell
docker compose exec -T symfony_app php -r "echo base64_encode(random_bytes(32)), PHP_EOL;"
```

## 6) Entrer dans le conteneur Symfony
Commande a lancer avant docker exec :
```powershell
docker compose up -d
```

Entrer dans le conteneur :
```powershell
docker exec -it Symfony_app bash
```

Permissions demandees :
```bash
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/var
```

Sortir du conteneur :
```bash
exit
```

## 7) Verification fonctionnelle
- http://localhost:8080 : application Symfony (OK)
- http://localhost:8081 : phpMyAdmin (OK)
- http://localhost:8082 : Adminer (OK)
- localhost:3306 : service local hors projet
- localhost:3307 : MySQL du projet

## 7.1) Authentification (admin/user)

Comptes de test crees via la commande Symfony :
```powershell
docker compose exec -T symfony_app php bin/console app:bootstrap-users
```

Comptes disponibles :
- Admin : admin@unit.local / Admin1234!
- User : user@unit.local / User1234!

URLs d'authentification et zones protegees :
- http://localhost:8080/login/admin : formulaire de connexion admin
- http://localhost:8080/login/user : formulaire de connexion utilisateur
- http://localhost:8080/admin : dashboard admin (ROLE_ADMIN requis)
- http://localhost:8080/user : dashboard user (ROLE_USER requis)
- http://localhost:8080/logout : deconnexion (POST)

Comportement attendu :
- Un utilisateur non connecte accedant a /admin ou /user est redirige vers la connexion.
- Un compte user ne peut pas se connecter depuis /login/admin.
- Un compte admin connecte depuis /login/admin arrive sur /admin.
- Un compte user connecte depuis /login/user arrive sur /user.

Verification base :
```powershell
docker compose exec -T symfony_db mysql -uSymfony -pSymfony -D Symfony -e "SHOW TABLES; SELECT COUNT(*) AS homepage_rows FROM homepage_content;"
```
Resultat : table homepage_content presente avec 1 ligne.

Verification Doctrine (schema 100% synchronise) :
```powershell
docker compose exec -T symfony_app php bin/console doctrine:schema:validate
```
Resultat attendu :
- [OK] The mapping files are correct.
- [OK] The database schema is in sync with the mapping files.

## 8) Captures d'ecran
Les captures sont stockées dans : [Screenshot](Screenshot)

Structure :
- Screenshot/01-setup
- Screenshot/02-docker-files
- Screenshot/03-symfony-install
- Screenshot/04-database
- Screenshot/05-tests

### Exemples de résultats attendus (maquettes SVG)

#### Page d'accueil (port 8090)
![Home page](Screenshot/examples/example-05-home-8080.svg)

#### Formulaire de connexion Administrateur
![Login Admin](Screenshot/examples/example-05-login-admin.svg)

#### Formulaire de connexion Utilisateur
![Login User](Screenshot/examples/example-05-login-user.svg)

#### Tableau de bord Administrateur
![Dashboard Admin](Screenshot/examples/example-05-admin-dashboard.svg)

#### Tableau de bord Utilisateur
![Dashboard User](Screenshot/examples/example-05-user-dashboard.svg)

#### Blocage d'un utilisateur sur la route admin
![Admin blocked for user](Screenshot/examples/example-05-admin-blocked-for-user.svg)

#### phpMyAdmin (port 8091)
![phpMyAdmin](Screenshot/examples/example-05-phpmyadmin-8081.svg)

#### Adminer (port 8082)
![Adminer](Screenshot/examples/example-05-adminer-8082.svg)

#### Preuve de persistance après redémarrage
![Persistence proof](Screenshot/examples/example-05-persistence-proof.svg)

Consigne rendue :
- Captures a chaque etape Docker/Symfony
- Integration dans ce README (partie setup)
- A partir du moment ou le code applicatif est en place, ne plus ajouter de captures techniques dans la section code
