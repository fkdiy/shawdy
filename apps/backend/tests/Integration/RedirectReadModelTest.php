<?php

namespace App\Tests\Integration;

use App\Infrastructure\Redis\RedisRedirectReadModel;

class RedirectReadModelTest extends IntegrationTestCase
{
    public function testStoresRedirectMappingInRedis(): void
    {
        $redis = self::getContainer()->get(\Redis::class);

        $readModel = self::getContainer()->get(RedisRedirectReadModel::class);

        $readModel->store(
            'abc123',
            'https://example.com',
        );

        self::assertSame(
            'https://example.com',
            $redis->get('redirect:abc123'),
        );
    }
}
