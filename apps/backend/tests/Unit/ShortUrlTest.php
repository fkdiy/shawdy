<?php

namespace App\Tests\Unit;

use App\Entity\ShortUrl;
use PHPUnit\Framework\TestCase;

class ShortUrlTest extends TestCase
{
    public function testTargetUrlCanBeSetAndRetrieved(): void
    {
        $shortUrl = new ShortUrl();

        $shortUrl->setTargetUrl('https://example.com');

        self::assertSame('https://example.com', $shortUrl->getTargetUrl());
    }

    public function testShortCodeCanBeSetAndRetrieved(): void
    {
        $shortUrl = new ShortUrl();

        $shortUrl->setShortCode('abc123');

        self::assertSame('abc123', $shortUrl->getShortCode());
    }
}
