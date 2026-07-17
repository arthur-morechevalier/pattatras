<?php

declare(strict_types=1);

namespace Pattatras\Tests;

use Pattatras\PattatrasConverter;
use Pattatras\PattatrasSequence;
use PHPUnit\Framework\TestCase;

/**
 * Tests d'intégration de PattatrasSequence.
 *
 * On vérifie que le parcours d'une plage applique correctement la règle
 * PattatrasConverter à chaque nombre, dans l'ordre.
 */
final class PattatrasSequenceTest extends TestCase
{
    /**
     * Sur une petite plage (1 à 15), la séquence doit produire exactement
     * les libellés attendus, dans l'ordre, y compris tous les cas de règles.
     */
    public function testGenereLaPlageAvecTousLesCas(): void
    {
        $sequence = new PattatrasSequence(new PattatrasConverter());

        $attendu = [
            '1', '2', 'Patte', '4', 'Tatras',
            'Patte', '7', '8', 'Patte', 'Tatras',
            '11', 'Patte', '13', '14', 'Pattatras',
        ];

        self::assertSame($attendu, $sequence->generate(1, 15));
    }

    /**
     * La plage officielle 1→6457 doit contenir exactement 6457 éléments,
     * commencer par « 1 » et se terminer par la valeur du nombre 6457.
     * (6457 n'est multiple ni de 3 ni de 5, donc il s'affiche tel quel.)
     */
    public function testPlageOfficielleComplete(): void
    {
        $sequence = new PattatrasSequence(new PattatrasConverter());

        $lignes = $sequence->generate(PattatrasSequence::DEBUT, PattatrasSequence::FIN);

        self::assertCount(6457, $lignes);
        self::assertSame('1', $lignes[0]);
        self::assertSame('6457', $lignes[6456]);
    }

    /**
     * Cas de bornes (pièges classiques du sujet) :
     *  - le tout premier nombre est 1 et non 0 ;
     *  - la toute fin de plage est correcte, avec ses trois derniers cas :
     *      6455 (multiple de 5)  → « Tatras »,
     *      6456 (multiple de 3)  → « Patte »,
     *      6457 (ni l'un ni l'autre) → le nombre lui-même.
     *
     * Ces indices reposent sur le fait que $lignes[i] correspond au nombre i+1.
     */
    public function testBornesDeLaPlageOfficielle(): void
    {
        $sequence = new PattatrasSequence(new PattatrasConverter());

        $lignes = $sequence->generate(PattatrasSequence::DEBUT, PattatrasSequence::FIN);

        // Borne de début : on commence bien à 1.
        self::assertSame('1', $lignes[0]);

        // Borne de fin : les trois derniers nombres 6455, 6456, 6457.
        self::assertSame('Tatras', $lignes[6454]);
        self::assertSame('Patte', $lignes[6455]);
        self::assertSame('6457', $lignes[6456]);
    }
}
