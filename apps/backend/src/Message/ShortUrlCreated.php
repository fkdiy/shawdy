<?php

namespace App\Message;

class ShortUrlCreated
{
    public function __construct(
        private string $shortCode,
        private string $targetUrl,
    ) {
    }

    public function getShortCode(): string
    {
        return $this->shortCode;
    }

    public function getTargetUrl(): string
    {
        return $this->targetUrl;
    }
}