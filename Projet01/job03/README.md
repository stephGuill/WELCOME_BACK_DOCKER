# Job 03 : Commandes de monitoring Docker

## Objectif
Tester les commandes de base de monitoring Docker dans le terminal VS Code.

## Commandes testées

### 1) Lister les conteneurs
Commande :
```bash
docker ps
```
Affiche uniquement les conteneurs en cours d’exécution.

Commande :
```bash
docker ps -a
```
Affiche tous les conteneurs (actifs + stoppés).

Résultat observé :
- 1 conteneur Mario en cours d’exécution (`sevenajay/mario:latest`)
- 1 conteneur `hello-world` stoppé

Sortie exacte :
```bash
CONTAINER ID   IMAGE                    COMMAND                  CREATED          STATUS                      PORTS                                      NAMES
c42347cc061b   sevenajay/mario:latest   "/docker-entrypoint.…"   22 minutes ago   Up 22 minutes               0.0.0.0:4545->80/tcp, [::]:4545->80/tcp   brave_elgamal
77cf9fec1e82   hello-world              "/hello"                 42 minutes ago   Exited (0) 42 minutes ago                                              intelligent_brattain
```

---

### 2) Lister les images Docker
Commande :
```bash
docker images
```
Affiche les images locales.

Commande avec option :
```bash
docker images -a
```
Affiche aussi les images intermédiaires (si présentes).

Résultat observé :
- `hello-world:latest`
- `sevenajay/mario:latest`

Sortie exacte :
```bash
IMAGE                    ID             DISK USAGE   CONTENT SIZE   EXTRA
hello-world:latest       452a468a4bf9   25.9kB       9.49kB         U
sevenajay/mario:latest   8541a39162f3   325MB        94.8MB         U
```

---

### 3) Lister les réseaux Docker
Commande :
```bash
docker network ls
```
Affiche les réseaux Docker disponibles.

Résultat observé :
- `bridge`
- `host`
- `none`

Sortie exacte :
```bash
NETWORK ID     NAME      DRIVER    SCOPE
bb040762dfb2   bridge    bridge    local
f21356eb64bd   host      host      local
c9f4b4c53966   none      null      local
```

---

## Récapitulatif
Ces commandes sont essentielles pour superviser un environnement Docker :
- `docker ps` / `docker ps -a` : état des conteneurs
- `docker images` / `docker images -a` : images disponibles
- `docker network ls` : réseaux Docker
