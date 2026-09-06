<?php

namespace App\Tests\Unit;

use App\Contract\RedirectReadModel;
use App\Message\ShortUrlCreated;
use App\MessageHandler\UpdateRedirectReadModel;
use PHPUnit\Framework\TestCase;

class RedirectReadModelTest extends TestCase
{
    public function testStoresRedirectMapping(): void
    {
        $readModel = $this->createMock(RedirectReadModel::class);

        $readModel
            ->expects(self::once())
            ->method('store')
            ->with(
                'abc123',
                'https://example.com',
            );

        $handler = new UpdateRedirectReadModel($readModel);

        $handler(
            new ShortUrlCreated(
                'abc123',
                'https://example.com',
            )
        );
    }
}
