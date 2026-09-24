<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ApiRateLimitTest extends FunctionalTestCase
{
    public function testShortUrlCreationIsRateLimited(): void
    {
        $client = static::createClient();

        for ($i = 0; $i < 10; ++$i) {
            $client->request(
                'POST',
                '/api/short_urls',
                server: [
                    'CONTENT_TYPE' => 'application/ld+json',
                    'REMOTE_ADDR' => '203.0.113.10',
                ],
                content: json_encode([
                    'targetUrl' => 'https://example.com/'.$i,
                ]),
            );

            self::assertResponseStatusCodeSame(201);
        }

        $client->request(
            'POST',
            '/api/short_urls',
            server: [
                'CONTENT_TYPE' => 'application/ld+json',
                'REMOTE_ADDR' => '203.0.113.10',
            ],
            content: json_encode([
                'targetUrl' => 'https://example.com/rate-limited',
            ]),
        );

        self::assertResponseStatusCodeSame(429);

        self::assertTrue(
            $client->getResponse()->headers->has('Retry-After')
        );
    }

    public function testRateLimitIsAppliedPerClient(): void
    {
        $client = static::createClient();

        for ($i = 0; $i < 10; ++$i) {
            $client->request(
                'POST',
                '/api/short_urls',
                server: [
                    'CONTENT_TYPE' => 'application/ld+json',
                    'REMOTE_ADDR' => '203.0.113.20',
                ],
                content: json_encode([
                    'targetUrl' => 'https://example.com/'.$i,
                ]),
            );

            self::assertResponseStatusCodeSame(201);
        }

        $client->request(
            'POST',
            '/api/short_urls',
            server: [
                'CONTENT_TYPE' => 'application/ld+json',
                'REMOTE_ADDR' => '198.51.100.20',
            ],
            content: json_encode([
                'targetUrl' => 'https://example.org',
            ]),
        );

        self::assertResponseStatusCodeSame(201);
    }

    public function testAbuseReportSubmissionIsRateLimited(): void
    {
        $client = static::createClient();

        $shortCode = $this->createShortUrl($client);

        for ($i = 0; $i < 3; ++$i) {
            $client->request(
                'POST',
                '/api/abuse_reports',
                server: [
                    'CONTENT_TYPE' => 'application/ld+json',
                    'REMOTE_ADDR' => '203.0.113.30',
                ],
                content: json_encode([
                    'shortUrl' => 'http://localhost/'.$shortCode,
                    'email' => sprintf('test%d@example.com', $i),
                    'message' => '',
                    'locale' => 'en',
                ]),
            );

            self::assertResponseStatusCodeSame(201);
        }

        $client->request(
            'POST',
            '/api/abuse_reports',
            server: [
                'CONTENT_TYPE' => 'application/ld+json',
                'REMOTE_ADDR' => '203.0.113.30',
            ],
            content: json_encode([
                'shortUrl' => 'http://localhost/'.$shortCode,
                'email' => 'blocked@example.com',
                'message' => '',
                'locale' => 'en',
            ]),
        );

        self::assertResponseStatusCodeSame(429);

        self::assertTrue(
            $client->getResponse()->headers->has('Retry-After')
        );
    }

    private function createShortUrl(KernelBrowser $client): string
    {
        $client->request(
            'POST',
            '/api/short_urls',
            server: [
                'CONTENT_TYPE' => 'application/ld+json',
                'REMOTE_ADDR' => '198.51.100.100',
            ],
            content: json_encode([
                'targetUrl' => 'https://example.com',
            ]),
        );

        self::assertResponseStatusCodeSame(201);

        $data = json_decode(
            $client->getResponse()->getContent(),
            true,
        );

        return $data['shortCode'];
    }
}
