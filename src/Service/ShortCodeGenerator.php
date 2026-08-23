<?php

namespace App\Service;

class ShortCodeGenerator
{
    public function __construct(
        private ShortCodeObfuscator $obfuscator,
    ) {
    }

    public function generate(int $id): string
    {
        // Obfuscate the ID
        $id = $this->obfuscator->obfuscate($id);

        // Convert the ID to a base62 string
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($characters);
        $shortCode = '';

        while ($id > 0) {
            $shortCode = $characters[$id % $base].$shortCode;
            $id = intdiv($id, $base);
        }

        return $shortCode;
    }
}
