<?php

declare(strict_types=1);

namespace Pattatras;

/**
 * Parcourt une plage de nombres et applique la règle « Pattatras » à chacun.
 *
 * Le converter est injecté dans le constructeur : cela découple le parcours
 * de la règle métier et facilite les tests.
 */
final class PattatrasSequence
{
    /** Borne de départ officielle du sujet. */
    public const DEBUT = 1;

    /** Borne de fin officielle du sujet. */
    public const FIN = 6457;

    public function __construct(
        private readonly PattatrasConverter $converter,
    ) {
    }

    /**
     * Produit, dans l'ordre, la ligne d'affichage de chaque nombre de la plage
     * [$debut, $fin] (bornes incluses).
     *
     * @return list<string>
     */
    public function generate(int $debut, int $fin): array
    {
        $lignes = [];

        for ($nombre = $debut; $nombre <= $fin; $nombre++) {
            $lignes[] = $this->converter->convert($nombre);
        }

        return $lignes;
    }
}
