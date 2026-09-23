<?php

namespace App\Message\Event;

final class ShortUrlDeleted
{
    public function __construct(
        private string $shortCode,
    ) {
    }

    public function getShortCode(): string
    {
        return $this->shortCode;
    }
}
