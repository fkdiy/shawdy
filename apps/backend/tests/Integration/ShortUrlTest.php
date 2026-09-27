<?php

namespace App\Tests\Integration;

use App\Entity\ShortUrl;
use Doctrine\ORM\EntityManagerInterface;

class ShortUrlTest extends IntegrationTestCase
{
    public function testShortUrlCanBePersisted(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $shortCode = 'abc123';

        $shortUrl = new ShortUrl();
        $shortUrl
            ->setTargetUrl('https://example.com')
            ->setShortCode($shortCode);

        $entityManager->persist($shortUrl);
        $entityManager->flush();

        $entityManager->clear();

        $stored = $entityManager
            ->getRepository(ShortUrl::class)
            ->findOneBy(['shortCode' => $shortCode]);

        self::assertNotNull($stored);
        self::assertNotNull($stored->getId());
        self::assertSame('https://example.com', $stored->getTargetUrl());
        self::assertSame($shortCode, $stored->getShortCode());
    }
}
