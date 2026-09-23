<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ShortUrl extends Constraint
{
    public string $message = 'errors.validation.shawdyUrl';

    public function __construct(
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        $this->message = $message ?? $this->message;

        parent::__construct(null, $groups, $payload);
    }
}
