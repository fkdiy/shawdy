<?php

namespace App\Validator;

use App\Repository\ShortUrlRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ShortUrlValidator extends ConstraintValidator
{
    public function __construct(
        private ShortUrlRepository $shortUrlRepository,
        #[Autowire('%kernel.environment%')]
        private string $environment,
    ) {
    }

    public function validate(mixed $shortUrl, Constraint $constraint): void
    {
        if (!$constraint instanceof ShortUrl) {
            throw new UnexpectedTypeException($constraint, ShortUrl::class);
        }

        // Let NotBlank / NotNull handle empty values.
        if (null === $shortUrl || '' === $shortUrl) {
            return;
        }

        if (!is_string($shortUrl)) {
            throw new UnexpectedValueException($shortUrl, 'string');
        }

        $host = parse_url($shortUrl, PHP_URL_HOST);
        $path = parse_url($shortUrl, PHP_URL_PATH);

        if (!is_string($host) || !is_string($path)) {
            $this->addViolation($constraint, $shortUrl);

            return;
        }

        if (!$this->isAllowedHost($host)) {
            $this->addViolation($constraint, $shortUrl);

            return;
        }

        $shortCode = trim($path, '/');

        if (
            '' === $shortCode
            || str_contains($shortCode, '/')
            || !preg_match('/^[a-zA-Z0-9]+$/', $shortCode)
        ) {
            $this->addViolation($constraint, $shortUrl);

            return;
        }

        if (!$this->shortUrlRepository->findOneBy([
            'shortCode' => $shortCode,
        ])) {
            $this->addViolation($constraint, $shortUrl);
        }
    }

    private function isAllowedHost(string $host): bool
    {
        if ('shawdy.de' === $host) {
            return true;
        }

        return in_array($this->environment, ['dev', 'test'], true)
            && in_array($host, ['localhost', '127.0.0.1', 'frankenphp'], true);
    }

    private function addViolation(
        ShortUrl $constraint,
        string $shortUrl,
    ): void {
        $this->context
            ->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $shortUrl)
            ->addViolation();
    }
}
