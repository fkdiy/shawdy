<?php

namespace App\Contract;

interface RedirectReadModel
{
    public function store(
        string $shortCode,
        string $targetUrl,
    ): void;
}
