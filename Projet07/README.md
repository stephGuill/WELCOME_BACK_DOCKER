# Projet07 - Docker et volume (Tic Tac Toe)

## Objectif
Héberger un jeu Tic Tac Toe avec Docker, enregistrer les résultats dans un volume nommé game-results, et vérifier la persistance du fichier results.json.

## Fichiers du projet
- [Projet07/Dockerfile](Dockerfile)
- [Projet07/default.conf](default.conf)
- [Projet07/index.html](index.html)
- [Projet07/save.php](save.php)
- [Projet07/results.json](results.json)
- [Projet07/images](images)

---

## 1) Construire l'image Docker
Commande :
```bash
docker build -t projet07-tictactoe:1.0 .
```

Image créée : projet07-tictactoe:1.0

---

## 2) Créer le volume persistant
Commande :
```bash
docker volume create game-results
```

Vérification :
```bash
docker volume ls
```

Sortie observée :
```text
DRIVER    VOLUME NAME
local     game-results
```

---

## 3) Lancer le conteneur avec mapping de port + volume
Commande :
```bash
docker run -d --name projet07-tictactoe -p 8080:80 -v game-results:/usr/share/nginx/html projet07-tictactoe:1.0
```

Explication :
- `-p 8080:80` : accès navigateur sur localhost:8080
- `-v game-results:/usr/share/nginx/html` : persistance des fichiers du jeu (dont results.json)

Vérification :
```bash
docker ps --filter name=projet07-tictactoe
```

Sortie observée :
```text
CONTAINER ID   IMAGE                    STATUS          PORTS                                     NAMES
d914a672b9c1   projet07-tictactoe:1.0   Up ...          0.0.0.0:8080->80/tcp                     projet07-tictactoe
```

---

## 4) Jouer et enregistrer des résultats
Le jeu est accessible via :
- http://localhost:8080

Le script save.php écrit les résultats dans results.json.

Exemple d'envoi de résultats (test terminal) :
```powershell
Invoke-RestMethod -Uri http://localhost:8080/save.php -Method Post -ContentType 'application/json' -Body '{"winner":"X","playedAt":"2026-04-01T02:00:00Z","board":["X","O","X","O","X","","","O",""]}'
Invoke-RestMethod -Uri http://localhost:8080/save.php -Method Post -ContentType 'application/json' -Body '{"winner":"draw","playedAt":"2026-04-01T02:05:00Z","board":["X","O","X","X","O","O","O","X","X"]}'
```

---

## 5) Afficher results.json (2 méthodes terminal)

### Méthode A : depuis le conteneur
```bash
docker exec projet07-tictactoe sh -c "cat /usr/share/nginx/html/results.json"
```

### Méthode B : depuis le volume Docker
```bash
docker run --rm -v game-results:/data alpine sh -c "cat /data/results.json"
```

Contenu observé :
```json
[
  {
    "winner": "X",
    "playedAt": "2026-04-01T02:00:00Z",
    "board": ["X", "O", "X", "O", "X", "", "", "O", ""]
  },
  {
    "winner": "draw",
    "playedAt": "2026-04-01T02:05:00Z",
    "board": ["X", "O", "X", "X", "O", "O", "O", "X", "X"]
  }
]
```

---

## 6) Accéder au système de fichiers du conteneur
Commande :
```bash
docker exec -it projet07-tictactoe sh
```

---

## 7) Actions via Docker Desktop (équivalents)
- Vérifier le volume : menu Volumes → game-results
- Vérifier le conteneur : menu Containers → projet07-tictactoe
- Voir résultats : ouvrir les fichiers montés / utiliser l'onglet terminal du conteneur
- Arrêter le conteneur : bouton Stop

---

## 8) Arrêter le conteneur
Commande :
```bash
docker stop projet07-tictactoe
```

Statut : conteneur arrêté avec succès.

---

## Captures réalisées

### Jeu Tic Tac Toe en exécution
![Jeu Tic Tac Toe](./images/Capture%20d’écran%20TicTacToe.png)

### Contenu du fichier results.json
![Contenu results.json](./images/Capture%20d’écran%20result.json.png)
