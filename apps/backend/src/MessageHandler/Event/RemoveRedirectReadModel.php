<?php

namespace App\MessageHandler\Event;

use App\Contract\RedirectReadModel;
use App\Message\Event\ShortUrlDeleted;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class RemoveRedirectReadModel
{
    public function __construct(
        private RedirectReadModel $redirectReadModel,
    ) {
    }

    public function __invoke(ShortUrlDeleted $message): void
    {
        $this->redirectReadModel->delete(
            $message->getShortCode()
        );
    }
}
