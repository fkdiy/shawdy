<?php

namespace App\Infrastructure\Redis;

use App\Contract\RedirectReadModel;

final class RedisRedirectReadModel implements RedirectReadModel
{
    public function __construct(
        private \Redis $redis,
    ) {
    }

    public function store(
        string $shortCode,
        string $targetUrl,
    ): void {
        $this->redis->set(
            'redirect:'.$shortCode,
            $targetUrl,
        );
    }
}
