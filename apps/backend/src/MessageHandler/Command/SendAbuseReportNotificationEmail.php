<?php

namespace App\MessageHandler\Command;

use App\Message\Command\SendAbuseReportNotification;
use App\Repository\AbuseReportRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;

#[AsMessageHandler]
final class SendAbuseReportNotificationEmail
{
    public function __construct(
        private AbuseReportRepository $abuseReportRepository,
        private MailerInterface $mailer,

        #[Autowire('%env(MAIL_FROM)%')]
        private string $mailFrom,

        #[Autowire('%env(ABUSE_REPORT_RECIPIENT)%')]
        private string $recipient,

        #[Autowire('%env(DEFAULT_URI)%')]
        private string $baseUrl,
    ) {
    }

    public function __invoke(SendAbuseReportNotification $message): void
    {
        $report = $this->abuseReportRepository->find(
            $message->getAbuseReportId()
        );

        if (null === $report) {
            return;
        }

        $shortUrl = sprintf(
            '%s/%s',
            rtrim($this->baseUrl, '/'),
            $report->getShortCode(),
        );

        $email = (new TemplatedEmail())
            ->from(new Address($this->mailFrom, 'Shawdy'))
            ->to($this->recipient)
            ->subject('New Shawdy abuse report')
            ->htmlTemplate('email/abuse-report/notification.html.twig')
            ->textTemplate('email/abuse-report/notification.txt.twig')
            ->context([
                'baseUrl' => rtrim($this->baseUrl, '/'),
                'shortUrl' => $shortUrl,
                'report' => $report,
            ]);

        $this->mailer->send($email);
    }
}
