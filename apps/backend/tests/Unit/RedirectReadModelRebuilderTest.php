<?php

namespace App\Tests\Unit;

use App\Contract\RedirectReadModel;
use App\Entity\ShortUrl;
use App\Repository\ShortUrlRepository;
use App\Service\RedirectReadModelRebuilder;
use PHPUnit\Framework\TestCase;

class RedirectReadModelRebuilderTest extends TestCase
{
    public function testDoesNotMarkReadModelAsInitializedWhenRebuildFails(): void
    {
        $shortUrl = $this->createStub(ShortUrl::class);

        $shortUrl
            ->method('getId')
            ->willReturn(1);

        $shortUrl
            ->method('getShortCode')
            ->willReturn('abc123');

        $shortUrl
            ->method('getTargetUrl')
            ->willReturn('https://example.com');

        $repository = $this->createMock(
            ShortUrlRepository::class
        );

        $repository
            ->expects(self::once())
            ->method('findBatchAfterId')
            ->willReturn([$shortUrl]);

        $readModel = $this->createMock(
            RedirectReadModel::class
        );

        $readModel
            ->expects(self::once())
            ->method('clear');

        $readModel
            ->expects(self::once())
            ->method('store')
            ->willThrowException(
                new \RuntimeException('Redis unavailable')
            );

        $readModel
            ->expects(self::never())
            ->method('markInitialized');

        $rebuilder = new RedirectReadModelRebuilder(
            $repository,
            $readModel,
        );

        $this->expectException(\RuntimeException::class);

        $rebuilder->rebuild();
    }
}
