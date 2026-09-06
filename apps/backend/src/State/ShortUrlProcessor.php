<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\ShortUrl;
use App\Message\ShortUrlCreated;
use App\Service\ShortCodeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @implements ProcessorInterface<ShortUrl, ShortUrl>
 */
class ShortUrlProcessor implements ProcessorInterface
{
    public function __construct(
        /**
         * @var ProcessorInterface<ShortUrl, ShortUrl>
         */
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $processor,
        private ShortCodeGenerator $shortCodeGenerator,
        private MessageBusInterface $messageBus,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): ShortUrl {
        // Wrap MariaDB persistance and Redis synchronization message in the same transaction
        return $this->entityManager->wrapInTransaction(
            function () use ($data, $operation, $uriVariables, $context): ShortUrl {
                // Persist the entity first so Doctrine can generate its ID.
                // The generated ID is required to create the short code.
                $data = $this->processor->process(
                    $data,
                    $operation,
                    $uriVariables,
                    $context
                );

                // The entity now has its database-generated ID.

                $shortCode = $this->shortCodeGenerator->generate($data->getId());

                $data->setShortCode($shortCode);

                // Persists the generated short code.
                $data = $this->processor->process(
                    $data,
                    $operation,
                    $uriVariables,
                    $context
                );

                // Persist the synchronization message in the same database transaction.
                $this->messageBus->dispatch(
                    new ShortUrlCreated(
                        $data->getShortCode(),
                        $data->getTargetUrl(),
                    )
                );

                return $data;
            }
        );
    }
}
