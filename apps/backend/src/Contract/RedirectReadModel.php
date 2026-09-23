<?php

namespace App\Contract;

interface RedirectReadModel
{
    public function store(
        string $shortCode,
        string $targetUrl,
    ): void;

    public function delete(string $shortCode): void;
}
