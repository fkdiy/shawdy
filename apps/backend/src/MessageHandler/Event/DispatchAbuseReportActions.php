<?php

namespace App\MessageHandler\Event;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\Event\AbuseReportReceived;
use App\Message\Command\SendAbuseReportConfirmation;
use App\Message\Command\SendAbuseReportNotification;

#[AsMessageHandler]
class DispatchAbuseReportActions
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(AbuseReportReceived $message): void
    {
        $this->messageBus->dispatch(
            new SendAbuseReportConfirmation(
                $message->getAbuseReportId()
            )
        );

        $this->messageBus->dispatch(
            new SendAbuseReportNotification(
                $message->getAbuseReportId()
            )
        );
    }
}