<?php

namespace App\Tests\Integration;

use App\Entity\ShortUrl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ShortUrlTest extends KernelTestCase
{
    public function testShortUrlCanBePersisted(): void
    {
        var_dump($_SERVER['APP_ENV'] ?? null);

        self::bootKernel();

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
        self::assertSame('https://example.com', $stored->getTargetUrl());
        self::assertSame($shortCode, $stored->getShortCode());
    }
}
