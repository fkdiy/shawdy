<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Psr\Cache\CacheItemPoolInterface;

abstract class FunctionalTestCase extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        /** @var CacheItemPoolInterface $rateLimiterCache */
        $rateLimiterCache = static::getContainer()->get(
            'cache.rate_limiter'
        );

        $rateLimiterCache->clear();

        self::ensureKernelShutdown();
    }
}
