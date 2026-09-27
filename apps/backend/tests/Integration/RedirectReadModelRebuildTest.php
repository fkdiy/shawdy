<?php

namespace App\Tests\Integration;

use App\Entity\ShortUrl;
use App\Infrastructure\Redis\RedisRedirectReadModel;
use App\Repository\ShortUrlRepository;
use App\Service\RedirectReadModelRebuilder;
use Doctrine\ORM\EntityManagerInterface;

class RedirectReadModelRebuildTest extends IntegrationTestCase
{
    private ShortUrlRepository $shortUrlRepository;
    private RedisRedirectReadModel $readModel;
    private RedirectReadModelRebuilder $rebuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shortUrlRepository = self::getContainer()->get(
            ShortUrlRepository::class
        );

        $this->readModel = self::getContainer()->get(
            RedisRedirectReadModel::class
        );

        $this->rebuilder = new RedirectReadModelRebuilder(
            $this->shortUrlRepository,
            $this->readModel,
        );
    }

    public function testRemovesStaleRedirectsDuringRebuild(): void
    {
        $redis = self::getContainer()->get(\Redis::class);

        $this->readModel->store(
            'stale123',
            'https://example.com',
        );

        $this->rebuilder->rebuild();

        self::assertFalse(
            $redis->get('redirect:stale123')
        );
    }

    public function testRebuildsRedirectReadModelFromSourceOfTruth(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $shortCode = 'abc123';
        $targetUrl = 'https://example.com';

        $shortUrl = new ShortUrl();
        $shortUrl
            ->setTargetUrl($targetUrl)
            ->setShortCode($shortCode);

        $entityManager->persist($shortUrl);
        $entityManager->flush();

        $redis = self::getContainer()->get(\Redis::class);

        $this->readModel->store(
            $shortCode,
            'https://wrong.example.com',
        );

        $this->rebuilder->rebuild();

        self::assertSame(
            $targetUrl,
            $redis->get('redirect:'.$shortCode),
        );
    }

    public function testMarksRedirectReadModelAsInitializedAfterRebuild(): void
    {
        $redis = self::getContainer()->get(\Redis::class);

        $this->readModel->clear();

        self::assertFalse(
            $redis->get('redirect_read_model:initialized')
        );

        $this->rebuilder->rebuild();

        self::assertSame(
            '1',
            $redis->get('redirect_read_model:initialized'),
        );

        $lastRebuildAt = \DateTimeImmutable::createFromFormat(
            \DateTimeInterface::ATOM,
            $redis->get('redirect_read_model:last_rebuild_at'),
        );

        self::assertInstanceOf(
            \DateTimeImmutable::class,
            $lastRebuildAt
        );
    }
}
