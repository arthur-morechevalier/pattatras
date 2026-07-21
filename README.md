# Pattatras

[![CI](https://github.com/arthur-morechevalier/pattatras/actions/workflows/ci.yml/badge.svg)](https://github.com/arthur-morechevalier/pattatras/actions/workflows/ci.yml)

Test technique développeur (CPAM de la Gironde).

Programme qui parcourt les nombres de 1 à 6457 et, pour chacun, affiche :
- « Pattatras » s'il est multiple de 3 et de 5
- « Patte » s'il est multiple de 3
- « Tatras » s'il est multiple de 5
- le nombre lui-même sinon

## Voir le résultat immédiatement

Le fichier [`resultat.txt`](resultat.txt) contient les 6457 lignes déjà générées
par le programme : il est consultable directement, sans rien installer ni exécuter.

## Récupérer le projet via Github

Le dépôt est public : https://github.com/arthur-morechevalier/pattatras

```bash
git clone https://github.com/arthur-morechevalier/pattatras.git
cd pattatras
```

Sans git, le bouton vert **Code → Download ZIP** sur la page du dépôt permet de
télécharger le projet, puis il suffit de décompresser l'archive.

L'historique des commits, consultable sur le dépôt, retrace le développement étape
par étape (une règle par cycle de test).

**[Voir l'historique des commits](https://github.com/arthur-morechevalier/pattatras/commits/main/)**
: il permet de constater l'ordre et l'évolution du projet, avec plusieurs étapes
poussées régulièrement plutôt qu'un unique envoi final.

## Lancer le programme

Trois façons, de la plus simple à la plus complète.
Toutes se lancent depuis le dossier du projet.

### 1. Avec Docker (aucune installation de PHP nécessaire)

Prérequis : [Docker](https://docs.docker.com/get-docker/) installé.

```bash
docker build -t pattatras .
docker run --rm pattatras
```

### 2. Avec PHP seul

Prérequis : PHP 8.1 ou plus (`php --version` pour vérifier).
Aucune dépendance n'est nécessaire pour exécuter le programme :

```bash
php bin/pattatras.php
```

### 3. Avec Composer

Prérequis : PHP 8.1 ou plus et [Composer](https://getcomposer.org/download/).

```bash
composer install
composer start
```

Pour enregistrer la sortie dans un fichier : `php bin/pattatras.php > resultat.txt`

## Lancer les tests

```bash
composer install
composer test   # PHPUnit
composer stan   # PHPStan niveau 10 (src, tests, bin)
```

Ces deux commandes sont également rejouées automatiquement à chaque push
via GitHub Actions (voir [`.github/workflows/ci.yml`](.github/workflows/ci.yml)).

## Organisation des fichiers

```
pattatras/
├── src/                       Le cœur du programme (la logique)
│   ├── PattatrasConverter.php   Applique la règle à UN nombre → renvoie le texte
│   └── PattatrasSequence.php    Parcourt la plage → renvoie la liste des textes
├── bin/
│   └── pattatras.php            Le programme à lancer : affiche le résultat
├── tests/                     Les tests automatiques
│   ├── PattatrasConverterTest.php   Vérifie les 4 règles, valeur par valeur
│   └── PattatrasSequenceTest.php    Vérifie le parcours complet et ses bornes
├── resultat.txt               Les 6457 lignes déjà générées (consultation directe)
├── Dockerfile                 Recette pour lancer le programme sans installer PHP
├── composer.json              Dépendances et raccourcis (test, stan, start)
├── phpunit.xml                Configuration des tests
├── phpstan.neon               Configuration de l'analyse statique
└── .github/workflows/ci.yml   Tests rejoués automatiquement à chaque envoi de code
```

La séparation `src` / `bin` est volontaire : `src` contient du code qui **calcule et
renvoie** des valeurs (donc testable automatiquement), `bin` contient uniquement
l'**affichage** à l'écran. C'est ce découpage qui rend les tests possibles.

## Technologies utilisées et pourquoi

| Outil | Rôle | Pourquoi ce choix |
|---|---|---|
| **PHP 8.1+** | Le langage | Technologie libre selon l'énoncé. PHP est disponible partout, ne demande aucune compilation, et le programme se lance d'une seule commande. Le typage strict des versions récentes (`declare(strict_types=1)`) évite les conversions implicites de types. |
| **PHPUnit 10** | Les tests automatiques | Outil de test standard de l'écosystème PHP. Il permet de vérifier en une commande que les règles sont toujours respectées, y compris après une modification. |
| **PHPStan (niveau 10)** | L'analyse statique | Il relit le code **sans l'exécuter** et signale les incohérences de types. Le niveau 10 est le plus strict. C'est un filet de sécurité complémentaire aux tests : il détecte des erreurs avant même le lancement. |
| **Composer** | Gestion des dépendances | Standard PHP. Il installe PHPUnit et PHPStan, et fournit des raccourcis lisibles : `composer test`, `composer stan`, `composer start`. |
| **Docker (PHP 8.5)** | Exécution sans installation | L'énoncé demande qu'une personne peu technique puisse lancer le programme. Docker évite d'avoir à installer PHP : deux commandes suffisent. L'image utilise PHP 8.5, la dernière version stable, pour un conteneur à jour. |
| **GitHub Actions (PHP 8.5)** | Intégration continue (CI) | À chaque envoi de code, les tests et l'analyse sont rejoués automatiquement sur une machine neuve, avec la même version de PHP que le conteneur Docker. Cela prouve que le projet fonctionne ailleurs que sur la machine du développeur. |
| **Git / GitHub** | Historique et livraison | L'historique des commits rend la démarche visible : une règle par étape, test d'abord. |

À noter : **le programme lui-même n'a aucune dépendance**. PHPUnit et PHPStan ne
servent qu'au développement. C'est pourquoi `php bin/pattatras.php` fonctionne
immédiatement, sans installation préalable.

## Démarche et choix techniques

**Développé en TDD** : chaque règle a été introduite par un test qui échoue,
puis par le code minimal qui le fait passer. L'historique des commits suit ce
découpage, une règle par cycle rouge/vert.

**Deux classes, deux responsabilités** :
- `PattatrasConverter` porte la règle métier (un nombre → une chaîne). Elle ne
  parcourt rien et n'affiche rien, ce qui la rend directement testable ;
- `PattatrasSequence` parcourt la plage et délègue la conversion au converter,
  qui lui est injecté dans le constructeur.

L'affichage, lui, reste dans `bin/pattatras.php` : la logique métier ne dépend
donc d'aucune sortie particulière.

**Le piège du multiple de 15** : le cas « multiple de 3 et de 5 » est testé en
premier dans `convert()`. Testé après, il ne serait jamais atteint (15 serait
affiché « Patte »). Un test dédié verrouille ce comportement.

**Les bornes** : la plage va de 1 à 6457 **inclus**, ce que des tests dédiés
vérifient explicitement (1, 6455 → « Tatras », 6456 → « Patte », 6457 → lui-même),
la borne haute étant l'erreur classique sur ce type de boucle.

**Garde défensive sur `generate()`** : bien que le programme n'appelle
`generate()` qu'avec la plage officielle 1→6457, la méthode reste réutilisable
avec des bornes arbitraires. Sans vérification, une plage inversée (par exemple
`generate(300, 100)`) renvoie silencieusement une liste vide au lieu de signaler
une erreur d'appel. Une `InvalidArgumentException` est donc levée si
`$debut > $fin`, avec un test dédié qui verrouille ce comportement.

**PHPStan niveau 10** (le plus strict) est appliqué à `src`, `tests` et `bin`,
avec `declare(strict_types=1)` partout.

### Une remarque sur l'énoncé

Le titre du sujet évoque des « nombres aléatoires », tandis que la consigne
précise demande de parcourir les nombres **de 1 à 6457**. J'ai retenu la
consigne précise, qui est la plus explicite. Le parcours étant paramétré
(`generate(int $debut, int $fin)`), traiter une autre plage ne demanderait
aucun changement de la règle métier.
