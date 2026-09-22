<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ValidShortUrl extends Constraint
{
    public string $message = 'errors.validation.shawdyUrl';

    public function __construct(
        ?array $groups = null,
        mixed $payload = null,
    ) {
        $this->message = $message ?? $this->message;

        parent::__construct(null, $groups, $payload);
    }
}
