<?php

namespace App\Service;

class ShortCodeObfuscator
{
    // Mersenne prime (2^61 - 1), defining the size of the obfuscation value space.
    private const int MODULUS = 2305843009213693951;

    // Multiplier used by the affine transformation.
    // Must be non-zero and coprime to the modulus to preserve uniqueness.
    private const int MULTIPLIER = 3;

    // Offset used by the affine transformation to further obfuscate the ID.
    private const int OFFSET = 15485863;

    public function obfuscate(int $id): int
    {
        return (self::MULTIPLIER * $id + self::OFFSET) % self::MODULUS;
    }
}
