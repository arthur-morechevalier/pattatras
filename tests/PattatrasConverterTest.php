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

    /**
     * Un nombre multiple de 5 (mais pas de 3) doit renvoyer « Tatras ».
     */
    #[DataProvider('multiplesDeCinqSeulement')]
    public function testMultipleDeCinqRenvoieTatras(int $nombre): void
    {
        $converter = new PattatrasConverter();

        self::assertSame('Tatras', $converter->convert($nombre));
    }

    /**
     * Quelques multiples de 5 qui ne sont PAS multiples de 3.
     *
     * @return array<string, array{int}>
     */
    public static function multiplesDeCinqSeulement(): array
    {
        return [
            'n=5'  => [5],
            'n=10' => [10],
            'n=20' => [20],
            'n=25' => [25],
            'n=50' => [50],
        ];
    }

    /**
     * Un nombre multiple de 3 ET de 5 (donc de 15) doit renvoyer « Pattatras ».
     */
    #[DataProvider('multiplesDeQuinze')]
    public function testMultipleDeTroisEtCinqRenvoiePattatras(int $nombre): void
    {
        $converter = new PattatrasConverter();

        self::assertSame('Pattatras', $converter->convert($nombre));
    }

    /**
     * Quelques multiples de 15 (à la fois multiples de 3 et de 5).
     *
     * @return array<string, array{int}>
     */
    public static function multiplesDeQuinze(): array
    {
        return [
            'n=15' => [15],
            'n=30' => [30],
            'n=45' => [45],
            'n=60' => [60],
            'n=90' => [90],
        ];
    }

    /**
     * Un nombre qui n'est multiple ni de 3 ni de 5 doit renvoyer
     * le nombre lui-même, sous forme de chaîne.
     */
    #[DataProvider('nombresSansMultiple')]
    public function testAutreNombreRenvoieLeNombreLuiMeme(int $nombre, string $attendu): void
    {
        $converter = new PattatrasConverter();

        self::assertSame($attendu, $converter->convert($nombre));
    }

    /**
     * Quelques nombres multiples ni de 3 ni de 5, avec l'affichage attendu.
     *
     * @return array<string, array{int, string}>
     */
    public static function nombresSansMultiple(): array
    {
        return [
            'n=1'  => [1, '1'],
            'n=2'  => [2, '2'],
            'n=4'  => [4, '4'],
            'n=7'  => [7, '7'],
            'n=8'  => [8, '8'],
            'n=13' => [13, '13'],
        ];
    }
}
