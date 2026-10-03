<?php

namespace App\MessageHandler\Command;

use App\Message\Command\SendAbuseReportConfirmation;
use App\Repository\AbuseReportRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;

#[AsMessageHandler]
final class SendAbuseReportConfirmationEmail
{
    public function __construct(
        private AbuseReportRepository $abuseReportRepository,
        private MailerInterface $mailer,

        #[Autowire('%env(MAIL_FROM)%')]
        private string $mailFrom,

        #[Autowire('%env(DEFAULT_URI)%')]
        private string $baseUrl,
    ) {
    }

    public function __invoke(SendAbuseReportConfirmation $message): void
    {
        $report = $this->abuseReportRepository->find(
            $message->getAbuseReportId()
        );

        if (null === $report) {
            return;
        }

        $baseUrl = rtrim($this->baseUrl, '/');

        $shortUrl = sprintf(
            '%s/%s',
            $baseUrl,
            $report->getShortCode(),
        );

        [$subject, $htmlTemplate, $textTemplate] = match ($report->getLocale()) {
            'de' => [
                'Ihre Missbrauchsmeldung ist eingegangen',
                'email/abuse-report/confirmation.de.html.twig',
                'email/abuse-report/confirmation.de.txt.twig',
            ],
            'en' => [
                'Abuse report received',
                'email/abuse-report/confirmation.en.html.twig',
                'email/abuse-report/confirmation.en.txt.twig',
            ],
            default => throw new \LogicException(sprintf('Unsupported abuse report locale "%s".', $report->getLocale())),
        };

        $email = (new TemplatedEmail())
            ->from(new Address($this->mailFrom, 'Shawdy'))
            ->to($report->getEmail())
            ->subject($subject)
            ->htmlTemplate($htmlTemplate)
            ->textTemplate($textTemplate)
            ->context([
                'baseUrl' => $baseUrl,
                'shortUrl' => $shortUrl,
                'report' => $report,
            ]);

        $this->mailer->send($email);
    }
}
