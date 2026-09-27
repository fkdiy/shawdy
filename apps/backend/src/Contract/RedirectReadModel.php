<?php

namespace App\Contract;

interface RedirectReadModel
{
    public function isInitialized(): bool;

    public function store(
        string $shortCode,
        string $targetUrl,
    ): void;

    public function delete(string $shortCode): void;

    public function clear(): void;

    public function markInitialized(\DateTimeImmutable $rebuiltAt): void;
}
