<?php

namespace App\Message\Command;

final class SendAbuseReportNotification
{
    public function __construct(
        private int $abuseReportId,
    ) {
    }

    public function getAbuseReportId(): int
    {
        return $this->abuseReportId;
    }
}
