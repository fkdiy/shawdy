<?php

namespace App\MessageHandler\Command;

use App\Message\Command\SendAbuseReportNotification;
use App\Repository\AbuseReportRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final class SendAbuseReportNotificationEmail
{
    public function __construct(
        private AbuseReportRepository $abuseReportRepository,
        private MailerInterface $mailer,
    ) {
    }

    public function __invoke(SendAbuseReportNotification $message): void
    {
        $report = $this->abuseReportRepository->find(
            $message->getAbuseReportId()
        );

        if ($report === null) {
            return;
        }

        $email = (new Email())
            ->from('noreply@shawdy.de')
            ->to('your-address@example.com')
            ->subject('New Shawdy abuse report')
            ->text(sprintf(
                "A new abuse report was submitted.\n\n"
                ."Short code: %s\n"
                ."Reporter: %s\n\n"
                ."Message:\n%s",
                $report->getShortCode(),
                $report->getEmail(),
                $report->getMessage(),
            ));

        $this->mailer->send($email);
    }
}