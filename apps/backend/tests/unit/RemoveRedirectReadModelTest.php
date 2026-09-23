<?php

namespace App\Tests\Unit;

use App\Contract\RedirectReadModel;
use App\Message\Event\ShortUrlDeleted;
use App\MessageHandler\Event\RemoveRedirectReadModel;
use PHPUnit\Framework\TestCase;

class RemoveRedirectReadModelTest extends TestCase
{
    public function testRemovesRedirectMapping(): void
    {
        $readModel = $this->createMock(RedirectReadModel::class);

        $readModel
            ->expects(self::once())
            ->method('delete')
            ->with('abc123');

        $handler = new RemoveRedirectReadModel($readModel);

        $handler(new ShortUrlDeleted('abc123'));
    }
}
