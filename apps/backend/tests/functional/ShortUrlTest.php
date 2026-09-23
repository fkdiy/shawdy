<?php

namespace App\Tests\Functional;

use App\Entity\ShortUrl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShortUrlTest extends WebTestCase
{
    public function testShortUrlCanBeCreatedThroughApi(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/short_urls',
            server: [
                'CONTENT_TYPE' => 'application/ld+json',
            ],
            content: json_encode([
                'targetUrl' => 'https://example.com',
            ]),
        );

        self::assertResponseStatusCodeSame(201);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        self::assertNotEmpty($data['shortCode']);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get(
            EntityManagerInterface::class
        );

        $stored = $entityManager
            ->getRepository(ShortUrl::class)
            ->findOneBy([
                'shortCode' => $data['shortCode'],
            ]);

        self::assertNotNull($stored);
        self::assertSame(
            'https://example.com',
            $stored->getTargetUrl()
        );
    }
}
