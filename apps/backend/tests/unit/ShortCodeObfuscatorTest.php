<?php

namespace App\Tests\Unit;

use App\Service\ShortCodeObfuscator;
use PHPUnit\Framework\TestCase;

class ShortCodeObfuscatorTest extends TestCase
{
    public function testEntityIdCanBeObfuscated(): void
    {
        $obfuscator = new ShortCodeObfuscator();

        $id = 123;

        $obfuscatedId = $obfuscator->obfuscate($id);

        self::assertNotSame($id, $obfuscatedId);
    }

    public function testSameEntityIdAlwaysProducesSameResult(): void
    {
        $obfuscator = new ShortCodeObfuscator();

        $id = 123;

        $firstObfuscatedId = $obfuscator->obfuscate($id);
        $secondObfuscatedId = $obfuscator->obfuscate($id);

        self::assertSame($firstObfuscatedId, $secondObfuscatedId);
    }

    public function testDifferentEntityIdsProduceDifferentResults(): void
    {
        $obfuscator = new ShortCodeObfuscator();

        $obfuscatedIds = [];

        foreach (range(1, 10000) as $id) {
            $obfuscatedIds[] = $obfuscator->obfuscate($id);
        }

        self::assertCount(
            count($obfuscatedIds),
            array_unique($obfuscatedIds),
        );
    }

    public function testObfuscatedIdIsWithinValidRange(): void
    {
        $obfuscator = new ShortCodeObfuscator();

        $modulus = 2305843009213693951;

        foreach ([0, 1, 2, 100, 123456, $modulus - 2, $modulus - 1] as $id) {
            $obfuscatedId = $obfuscator->obfuscate($id);

            self::assertGreaterThanOrEqual(0, $obfuscatedId);
            self::assertLessThan($modulus, $obfuscatedId);
        }
    }
}
