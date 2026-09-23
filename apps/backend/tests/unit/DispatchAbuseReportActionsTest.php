<?php

namespace App\Tests\Unit;

use App\Message\Command\SendAbuseReportConfirmation;
use App\Message\Command\SendAbuseReportNotification;
use App\Message\Event\AbuseReportReceived;
use App\MessageHandler\Event\DispatchAbuseReportActions;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class DispatchAbuseReportActionsTest extends TestCase
{
    public function testDispatchesConfirmationAndNotificationCommands(): void
    {
        $bus = $this->createMock(MessageBusInterface::class);

        $dispatchedMessages = [];

        $bus
            ->expects(self::exactly(2))
            ->method('dispatch')
            ->willReturnCallback(
                function (object $message) use (&$dispatchedMessages): Envelope {
                    $dispatchedMessages[] = $message;

                    return new Envelope($message);
                }
            );

        $handler = new DispatchAbuseReportActions($bus);

        $handler(new AbuseReportReceived(42));

        self::assertCount(2, $dispatchedMessages);

        self::assertInstanceOf(
            SendAbuseReportConfirmation::class,
            $dispatchedMessages[0]
        );

        self::assertInstanceOf(
            SendAbuseReportNotification::class,
            $dispatchedMessages[1]
        );

        self::assertSame(
            42,
            $dispatchedMessages[0]->getAbuseReportId()
        );

        self::assertSame(
            42,
            $dispatchedMessages[1]->getAbuseReportId()
        );
    }
}
