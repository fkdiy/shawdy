<?php

namespace App\MessageHandler\Command;

use App\Message\Command\SendAbuseReportConfirmation;
use App\Repository\AbuseReportRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final class SendAbuseReportConfirmationEmail
{
    public function __construct(
        private AbuseReportRepository $abuseReportRepository,
        private MailerInterface $mailer,
    ) {
    }

    public function __invoke(SendAbuseReportConfirmation $message): void
    {
        $report = $this->abuseReportRepository->find(
            $message->getAbuseReportId()
        );

        if ($report === null) {
            return;
        }

        switch ($report->getLocale()) {
            case 'de':
                $subject = 'Ihre Missbrauchsmeldung ist eingegangen';
                $text =
                    "Vielen Dank für Ihre Meldung.\n\n"
                    ."Ich habe Ihre Meldung erhalten und werde sie zeitnah prüfen.\n\n"
                    ."Gemeldete Short-URL: https://shawdy.de/{$report->getShortCode()}\n\n"
                    ."-----\n\n"
                    ."Fabian König\n"
                    ."c/o Impressumservice Dein-Impressum\n"
                    ."Stettiner Str. 41\n"
                    ."35410 Hungen\n\n"
                    ."Telefon: +49 15679 311106\n"  
                    ."E-Mail: contact@shawdy.de";
                break;

            case 'en':
                $subject = 'Abuse report received';
                $text =
                    "Thank you for your report.\n\n"
                    ."We have received your report and will review it promptly.\n\n"
                    ."Reported short URL: https://shawdy.de/{$report->getShortCode()}\n\n"
                    ."-----\n\n"
                    ."Fabian König\n"
                    ."c/o Impressumservice Dein-Impressum\n"
                    ."Stettiner Str. 41\n"
                    ."35410 Hungen\n\n"
                    ."Phone: +49 15679 311106\n"  
                    ."Email: contact@shawdy.de";
                break;

            default:
                throw new \LogicException(sprintf(
                    'Unsupported abuse report locale "%s".',
                    $report->getLocale(),
                ));
        }

        $email = (new Email())
            ->from('noreply@shawdy.de')
            ->to($report->getEmail())
            ->subject($subject)
            ->text($text);

        $this->mailer->send($email);
    }
}