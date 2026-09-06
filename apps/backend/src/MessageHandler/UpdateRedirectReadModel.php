<?php

namespace App\MessageHandler;

use App\Message\ShortUrlCreated;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateRedirectReadModel
{
    public function __invoke(ShortUrlCreated $message)
    {
        // ... do some work - like updating the redirect read model
    }
}