# Job 01 : Mon premier conteneur

## Démarrage de votre premier conteneur Docker

Pour démarrer votre premier conteneur, utilisez la commande suivante :

```bash
docker run hello-world
```

Vous devriez obtenir un message similaire à celui-ci :

---
Unable to find image 'hello-world:latest' locally
latest: Pulling from library/hello-world
...
Hello from Docker!
This message shows that your installation appears to be working correctly.
...
---

### Explication du fonctionnement

Lorsque vous utilisez cette commande, le daemon* Docker va chercher si l'image `hello-world` est disponible en local. Si ce n'est pas le cas, il va la récupérer sur la registry Docker officielle.

#### Qu'est-ce qu'un daemon ?

En informatique, un daemon (ou « démon » en français) est un type de programme particulier qui s'exécute en arrière-plan, plutôt que sous le contrôle direct d'un utilisateur interactif. C'est un peu le travailleur de l'ombre de votre système d'exploitation.

**Caractéristiques principales d'un daemon :**
- **Invisible** : Il n'a pas d'interface graphique et n'est pas attaché à une fenêtre ou un terminal spécifique.
- **Autonome** : Il est souvent lancé au démarrage du système (boot) et reste actif jusqu'à l'extinction de la machine.
- **Réactif** : Il attend patiemment qu'un événement précis se produise (une requête réseau, un branchement de périphérique, une heure précise) pour agir.

---

Pour plus d'exemples et d'idées, visitez : [https://docs.docker.com/get-started/](https://docs.docker.com/get-started/)
