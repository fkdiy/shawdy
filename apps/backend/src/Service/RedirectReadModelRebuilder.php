<?php

namespace App\Service;

use App\Contract\RedirectReadModel;
use App\Repository\ShortUrlRepository;

final class RedirectReadModelRebuilder
{
    private const BATCH_SIZE = 500;

    public function __construct(
        private ShortUrlRepository $shortUrlRepository,
        private RedirectReadModel $redirectReadModel,
    ) {
    }

    public function rebuild(): int
    {
        $this->redirectReadModel->clear();

        $lastId = 0;
        $count = 0;

        while (true) {
            $shortUrls = $this->shortUrlRepository->findBatchAfterId(
                $lastId,
                self::BATCH_SIZE,
            );

            if ([] === $shortUrls) {
                break;
            }

            foreach ($shortUrls as $shortUrl) {
                $id = $shortUrl->getId();
                $shortCode = $shortUrl->getShortCode();
                $targetUrl = $shortUrl->getTargetUrl();

                if (
                    null === $id
                    || null === $shortCode
                    || null === $targetUrl
                ) {
                    continue;
                }

                $this->redirectReadModel->store(
                    $shortCode,
                    $targetUrl,
                );

                $lastId = $id;
                ++$count;
            }
        }

        $this->redirectReadModel->markInitialized(
            new \DateTimeImmutable(),
        );

        return $count;
    }
}
