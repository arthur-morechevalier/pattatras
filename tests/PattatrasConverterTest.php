<?php

declare(strict_types=1);

namespace Pattatras\Tests;

use Pattatras\PattatrasConverter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires de la règle métier PattatrasConverter::convert().
 *
 * On teste la règle de conversion d'un nombre en chaîne d'affichage,
 * indépendamment de tout affichage ou parcours de plage.
 */
final class PattatrasConverterTest extends TestCase
{
    /**
     * Un nombre multiple de 3 (mais pas de 5) doit renvoyer « Patte ».
     */
    #[DataProvider('multiplesDeTroisSeulement')]
    public function testMultipleDeTroisRenvoiePatte(int $nombre): void
    {
        $converter = new PattatrasConverter();

        self::assertSame('Patte', $converter->convert($nombre));
    }

    /**
     * Quelques multiples de 3 qui ne sont PAS multiples de 5.
     *
     * @return array<string, array{int}>
     */
    public static function multiplesDeTroisSeulement(): array
    {
        return [
            'n=3'  => [3],
            'n=6'  => [6],
            'n=9'  => [9],
            'n=12' => [12],
            'n=18' => [18],
        ];
    }
}
