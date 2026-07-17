<?php

declare(strict_types=1);

namespace Pattatras;

/**
 * Règle métier du jeu « Pattatras ».
 *
 * Convertit un nombre entier en la chaîne à afficher selon les règles :
 *  - multiple de 3 et 5 → « Pattatras »
 *  - multiple de 3      → « Patte »
 *  - multiple de 5      → « Tatras »
 *  - sinon              → le nombre lui-même
 *
 * Cette classe ne fait QUE la conversion : elle ne parcourt rien et n'affiche
 * rien, ce qui la rend simple à tester unitairement.
 */
final class PattatrasConverter
{
    /**
     * Convertit un nombre en sa représentation d'affichage.
     */
    public function convert(int $nombre): string
    {
        // Multiple de 3 ET de 5 → « Pattatras ».
        // Testé en premier car ce cas prime sur « Patte » et « Tatras ».
        if ($nombre % 3 === 0 && $nombre % 5 === 0) {
            return 'Pattatras';
        }

        // Multiple de 3 → « Patte ».
        if ($nombre % 3 === 0) {
            return 'Patte';
        }

        // Multiple de 5 → « Tatras ».
        if ($nombre % 5 === 0) {
            return 'Tatras';
        }

        // Cas par défaut : le nombre lui-même (affiné par les règles suivantes).
        return (string) $nombre;
    }
}
