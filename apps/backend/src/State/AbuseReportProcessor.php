<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\AbuseReportInput;
use App\Entity\AbuseReport;
use App\Message\Event\AbuseReportReceived;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @implements ProcessorInterface<AbuseReportInput, AbuseReport>
 */
class AbuseReportProcessor implements ProcessorInterface
{
    public function __construct(
        /**
         * @var ProcessorInterface<AbuseReport, AbuseReport>
         */
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $processor,
        private MessageBusInterface $messageBus,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): AbuseReport {
        // Wrap MariaDB persistance and send mail message in the same transaction
        return $this->entityManager->wrapInTransaction(
            function () use ($data, $operation, $uriVariables, $context): AbuseReport {
                // Extract short code from AbuseReportInput DTO
                $shortCode = $this->extractShortCode($data->getShortUrl());

                // Create new AbuseReport
                $report = new AbuseReport();
                $report->setShortCode($shortCode);
                $report->setEmail($data->getEmail());
                $report->setMessage($data->getMessage());
                $report->setLocale($data->getLocale());

                // Persist the entity
                $report = $this->processor->process(
                    $report,
                    $operation,
                    $uriVariables,
                    $context
                );

                // Send AbuseReportReceived message in the same database transaction.
                $this->messageBus->dispatch(
                    new AbuseReportReceived($report->getId())
                );

                return $report;
            }
        );
    }

    private function extractShortCode(string $shortUrl): string
    {
        $path = parse_url($shortUrl, PHP_URL_PATH);

        return trim($path, '/');
    }
}
