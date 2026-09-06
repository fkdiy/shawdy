<?php

namespace App\Tests\Functional;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedirectReadModelTest extends WebTestCase
{
    public function testCreatingShortUrlQueuesRedirectReadModelMessage(): void
    {
        $client = static::createClient();

        /** @var Connection $connection */
        $connection = self::getContainer()
            ->get('doctrine')
            ->getConnection();

        self::assertSame(
            0,
            (int) $connection->fetchOne(
                'SELECT COUNT(*) FROM messenger_messages'
            )
        );

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

        self::assertSame(
            1,
            (int) $connection->fetchOne(
                'SELECT COUNT(*) FROM messenger_messages'
            )
        );
    }
}
