<?php

namespace App\Tests\Integration;

use App\Entity\ShortUrl;
use App\Message\Event\ShortUrlDeleted;
use App\Service\ShortUrlDeleter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class ShortUrlDeletionTest extends IntegrationTestCase
{
    public function testDeletesShortUrlAndDispatchesEvent(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get(
            EntityManagerInterface::class
        );

        $messageBus = $this->createMock(MessageBusInterface::class);

        $messageBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(
                self::callback(
                    static fn (object $message): bool => $message instanceof ShortUrlDeleted
                        && 'abc123' === $message->getShortCode()
                )
            )
            ->willReturnCallback(
                static fn (object $message): Envelope => new Envelope($message)
            );

        $shortUrl = new ShortUrl();
        $shortUrl
            ->setShortCode('abc123')
            ->setTargetUrl('https://example.com');

        $entityManager->persist($shortUrl);
        $entityManager->flush();

        $service = new ShortUrlDeleter(
            $entityManager->getRepository(ShortUrl::class),
            $entityManager,
            $messageBus,
        );

        $deleted = $service->delete('abc123');

        self::assertTrue($deleted);

        $entityManager->clear();

        $stored = $entityManager
            ->getRepository(ShortUrl::class)
            ->findOneBy(['shortCode' => 'abc123']);

        self::assertNull($stored);
    }

    public function testDispatchesDeleteEventWhenShortUrlDoesNotExist(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get(
            EntityManagerInterface::class
        );

        $messageBus = $this->createMock(MessageBusInterface::class);

        $messageBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(
                self::callback(
                    static fn (object $message): bool => $message instanceof ShortUrlDeleted
                        && 'missing123' === $message->getShortCode()
                )
            )
            ->willReturnCallback(
                static fn (object $message): Envelope => new Envelope($message)
            );

        $service = new ShortUrlDeleter(
            $entityManager->getRepository(ShortUrl::class),
            $entityManager,
            $messageBus,
        );

        $deleted = $service->delete('missing123');

        self::assertFalse($deleted);
    }
}
