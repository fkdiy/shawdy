<?php

declare(strict_types=1);

require dirname(__DIR__).'/vendor/autoload.php';

if (($_SERVER['APP_DEBUG'] ?? '0') === '1') {
    umask(0000);
}
