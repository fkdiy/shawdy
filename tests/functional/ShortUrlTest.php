<?php

namespace App\Tests\Functional;

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

        $shortCode = $data['shortCode'];

        $client->request(
            'GET',
            '/api/short_urls/'.$shortCode,
        );

        self::assertResponseIsSuccessful();

        $data = json_decode(
            $client->getResponse()->getContent(),
            true
        );

        self::assertSame(
            'https://example.com',
            $data['targetUrl']
        );
    }
}
