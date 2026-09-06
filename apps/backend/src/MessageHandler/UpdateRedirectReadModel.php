<?php

namespace App\MessageHandler;

use App\Contract\RedirectReadModel;
use App\Message\ShortUrlCreated;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateRedirectReadModel
{
    public function __construct(
        private RedirectReadModel $redirectReadModel,
    ) {
    }

    public function __invoke(ShortUrlCreated $message): void
    {
        $this->redirectReadModel->store(
            $message->getShortCode(),
            $message->getTargetUrl(),
        );
    }
}
