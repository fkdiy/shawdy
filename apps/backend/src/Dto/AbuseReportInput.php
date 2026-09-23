<?php

namespace App\Dto;

use App\Validator as ShawdyAssert;
use Symfony\Component\Validator\Constraints as Assert;

class AbuseReportInput
{
    #[Assert\NotBlank]
    #[Assert\Url(requireTld: false)]
    #[ShawdyAssert\ShortUrl]
    private ?string $shortUrl = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 255)]
    private ?string $email = null;

    #[Assert\Length(max: 5000)]
    private ?string $message = null;

    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^(de|en)$/',
        message: 'error.validation.locale'
    )]
    private ?string $locale = null;

    public function getShortUrl(): ?string
    {
        return $this->shortUrl;
    }

    public function setShortUrl(string $shortUrl): static
    {
        $this->shortUrl = $shortUrl;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }
}
