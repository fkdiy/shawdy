<?php

namespace App\Message\Event;

class AbuseReportReceived
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
