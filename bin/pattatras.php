<?php

declare(strict_types=1);

/**
 * Point d'entrée exécutable du programme Pattatras.
 *
 * Affiche, une valeur par ligne, le résultat de la règle « Pattatras »
 * pour chaque nombre de la plage officielle 1 → 6457.
 *
 * Utilisation :
 *   php bin/pattatras.php
 *   (ou "composer start")
 */

use Pattatras\PattatrasConverter;
use Pattatras\PattatrasSequence;

// Chargement des classes :
// - si l'autoloader Composer est présent (après « composer install »), on l'utilise ;
// - sinon, on charge directement les fichiers sources.
//   => le programme fonctionne donc même sans aucune installation.
$autoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($autoload)) {
    require $autoload;
} else {
    require __DIR__ . '/../src/PattatrasConverter.php';
    require __DIR__ . '/../src/PattatrasSequence.php';
}

$sequence = new PattatrasSequence(new PattatrasConverter());

// Un affichage clair : une valeur par ligne.
foreach ($sequence->generate(PattatrasSequence::DEBUT, PattatrasSequence::FIN) as $ligne) {
    echo $ligne, PHP_EOL;
}
