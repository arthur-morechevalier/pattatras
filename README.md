# Pattatras

[![CI](https://github.com/arthur-morechevalier/pattatras/actions/workflows/ci.yml/badge.svg)](https://github.com/arthur-morechevalier/pattatras/actions/workflows/ci.yml)

Test technique développeur — CPAM de la Gironde.

Programme qui parcourt les nombres de 1 à 6457 et, pour chacun, affiche :
- « Pattatras » s'il est multiple de 3 et de 5
- « Patte » s'il est multiple de 3
- « Tatras » s'il est multiple de 5
- le nombre lui-même sinon

## Voir le résultat immédiatement

Le fichier [`resultat.txt`](resultat.txt) contient les 6457 lignes déjà générées
par le programme : il est consultable directement, sans rien installer ni exécuter.

## Récupérer le projet

Le dépôt est public : https://github.com/arthur-morechevalier/pattatras

```bash
git clone https://github.com/arthur-morechevalier/pattatras.git
cd pattatras
```

Sans git, le bouton vert **Code → Download ZIP** sur la page du dépôt permet de
télécharger le projet, puis il suffit de décompresser l'archive.

L'historique des commits, consultable sur le dépôt, retrace le développement étape
par étape (une règle par cycle de test).

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

**PHPStan niveau 10** (le plus strict) est appliqué à `src`, `tests` et `bin`,
avec `declare(strict_types=1)` partout.

### Une remarque sur l'énoncé

Le titre du sujet évoque des « nombres aléatoires », tandis que la consigne
précise demande de parcourir les nombres **de 1 à 6457**. J'ai retenu la
consigne précise, qui est la plus explicite. Le parcours étant paramétré
(`generate(int $debut, int $fin)`), traiter une autre plage ne demanderait
aucun changement de la règle métier.
