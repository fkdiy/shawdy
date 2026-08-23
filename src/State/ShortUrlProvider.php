<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\ShortUrl;
use App\Repository\ShortUrlRepository;

/**
 * @implements ProviderInterface<ShortUrl>
 */
final class ShortUrlProvider implements ProviderInterface
{
    public function __construct(
        private ShortUrlRepository $repository,
    ) {
    }

    public function provide(
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): object|array|null {
        return $this->repository->findOneBy([
            'shortCode' => $uriVariables['shortCode'],
        ]);
    }
}
