<?php

namespace App\Tests\Functional;

use App\Entity\ShortUrl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbuseReportTest extends WebTestCase
{
    public function testAbuseReportCanBeCreatedThroughApi(): void
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get(
            EntityManagerInterface::class
        );

        $shortUrl = new ShortUrl();
        $shortUrl
            ->setTargetUrl('https://example.com')
            ->setShortCode('abc123');

        $entityManager->persist($shortUrl);
        $entityManager->flush();

        $client->request(
            'POST',
            '/api/abuse_reports',
            server: [
                'CONTENT_TYPE' => 'application/ld+json',
            ],
            content: json_encode([
                'shortUrl' => 'http://localhost/abc123',
                'email' => 'test@example.com',
                'message' => 'Test report',
                'locale' => 'de',
            ]),
        );

        self::assertResponseStatusCodeSame(201);
    }

    public function testEmptyUrlIsRejected(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/abuse_reports',
            server: [
                'CONTENT_TYPE' => 'application/ld+json',
            ],
            content: json_encode([
                'shortUrl' => '',
                'email' => 'test@example.com',
                'message' => 'Test report',
                'locale' => 'de',
            ]),
        );

        self::assertResponseStatusCodeSame(422);
    }

    public function testInvalidUrlIsRejected(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/abuse_reports',
            server: [
                'CONTENT_TYPE' => 'application/ld+json',
            ],
            content: json_encode([
                'shortUrl' => 'not-a-url',
                'email' => 'test@example.com',
                'message' => 'Test report',
                'locale' => 'de',
            ]),
        );

        self::assertResponseStatusCodeSame(422);
    }

    public function testExternalUrlIsRejected(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/abuse_reports',
            server: [
                'CONTENT_TYPE' => 'application/ld+json',
            ],
            content: json_encode([
                'shortUrl' => 'https://example.com/abc123',
                'email' => 'test@example.com',
                'message' => 'Test report',
                'locale' => 'de',
            ]),
        );

        self::assertResponseStatusCodeSame(422);
    }

    public function testEmptyEmailIsRejected(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/abuse_reports',
            server: [
                'CONTENT_TYPE' => 'application/ld+json',
            ],
            content: json_encode([
                'shortUrl' => 'http://localhost/abc123',
                'email' => '',
                'message' => 'Test report',
                'locale' => 'de',
            ]),
        );

        self::assertResponseStatusCodeSame(422);
    }

    public function testInvalidEmailIsRejected(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/abuse_reports',
            server: [
                'CONTENT_TYPE' => 'application/ld+json',
            ],
            content: json_encode([
                'shortUrl' => 'http://localhost/abc123',
                'email' => 'not-an-email',
                'message' => 'Test report',
                'locale' => 'de',
            ]),
        );

        self::assertResponseStatusCodeSame(422);
    }

    public function testEmptyLocaleIsRejected(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/abuse_reports',
            server: [
                'CONTENT_TYPE' => 'application/ld+json',
            ],
            content: json_encode([
                'shortUrl' => 'http://localhost/abc123',
                'email' => 'test@example.com',
                'message' => 'Test report',
                'locale' => 'de',
            ]),
        );

        self::assertResponseStatusCodeSame(422);
    }

    public function testInvalidLocaleIsRejected(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/abuse_reports',
            server: [
                'CONTENT_TYPE' => 'application/ld+json',
            ],
            content: json_encode([
                'shortUrl' => 'http://localhost/abc123',
                'email' => 'test@example.com',
                'message' => 'Test report',
                'locale' => 'not-a-locale',
            ]),
        );

        self::assertResponseStatusCodeSame(422);
    }

    public function testUnknownShortCodeIsRejected(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/abuse_reports',
            server: [
                'CONTENT_TYPE' => 'application/ld+json',
            ],
            content: json_encode([
                'shortUrl' => 'http://localhost/doesnotexist',
                'email' => 'test@example.com',
                'message' => 'Test report',
                'locale' => 'de',
            ]),
        );

        self::assertResponseStatusCodeSame(422);
    }
}