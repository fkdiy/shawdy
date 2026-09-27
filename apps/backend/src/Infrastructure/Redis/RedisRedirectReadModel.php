<?php

namespace App\Infrastructure\Redis;

use App\Contract\RedirectReadModel;

final class RedisRedirectReadModel implements RedirectReadModel
{
    private const REDIRECT_PREFIX = 'redirect:';
    private const INITIALIZED_KEY = 'redirect_read_model:initialized';
    private const LAST_REBUILD_KEY = 'redirect_read_model:last_rebuild_at';

    public function __construct(
        private \Redis $redis,
    ) {
    }

    public function isInitialized(): bool
    {
        return '1' === $this->redis->get(
            self::INITIALIZED_KEY
        );
    }

    public function store(
        string $shortCode,
        string $targetUrl,
    ): void {
        $this->redis->set(
            self::REDIRECT_PREFIX.$shortCode,
            $targetUrl,
        );
    }

    public function delete(string $shortCode): void
    {
        $this->redis->del(self::REDIRECT_PREFIX.$shortCode);
    }

    public function clear(): void
    {
        $iterator = null;

        while (false !== ($keys = $this->redis->scan(
            $iterator,
            self::REDIRECT_PREFIX.'*',
            500,
        ))) {
            if ([] === $keys) {
                continue;
            }

            $this->redis->del($keys);
        }

        $this->redis->del(
            self::INITIALIZED_KEY,
            self::LAST_REBUILD_KEY,
        );
    }

    public function markInitialized(
        \DateTimeImmutable $rebuiltAt,
    ): void {
        $this->redis
            ->multi()
            ->set(
                self::LAST_REBUILD_KEY,
                $rebuiltAt->format(\DateTimeInterface::ATOM),
            )
            ->set(
                self::INITIALIZED_KEY,
                '1',
            )
            ->exec();
    }
}
