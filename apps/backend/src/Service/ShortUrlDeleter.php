<?php

namespace App\Service;

use App\Message\Event\ShortUrlDeleted;
use App\Repository\ShortUrlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class ShortUrlDeleter
{
    public function __construct(
        private ShortUrlRepository $shortUrlRepository,
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $messageBus,
    ) {
    }

    public function delete(string $shortCode): bool
    {
        return $this->entityManager->wrapInTransaction(
            function () use ($shortCode): bool {
                $shortUrl = $this->shortUrlRepository->findOneBy([
                    'shortCode' => $shortCode,
                ]);

                if (null !== $shortUrl) {
                    $this->entityManager->remove($shortUrl);
                    $this->entityManager->flush();
                }

                $this->messageBus->dispatch(
                    new ShortUrlDeleted($shortCode)
                );

                return null !== $shortUrl;
            }
        );
    }
}
