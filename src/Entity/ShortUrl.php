<?php

namespace App\Entity;

use App\Repository\ShortUrlRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShortUrlRepository::class)]
class ShortUrl
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $targetUrl = null;

    #[ORM\Column(length: 32, unique: true)]
    private ?string $shortCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTargetUrl(): ?string
    {
        return $this->targetUrl;
    }

    public function setTargetUrl(string $targetUrl): static
    {
        $this->targetUrl = $targetUrl;

        return $this;
    }

    public function getShortCode(): ?string
    {
        return $this->shortCode;
    }

    public function setShortCode(string $shortCode): static
    {
        $this->shortCode = $shortCode;

        return $this;
    }
}
