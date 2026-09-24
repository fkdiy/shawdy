<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\ShortUrlRepository;
use App\State\ShortUrlProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            processor: ShortUrlProcessor::class,
        ),
    ],
)]
#[ORM\Entity(repositoryClass: ShortUrlRepository::class)]
class ShortUrl
{
    #[ApiProperty(identifier: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Url(
        protocols: ['http', 'https'],
    )]
    #[Assert\Length(max: 2048)]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $targetUrl = null;

    #[ApiProperty(identifier: true, writable: false)]
    #[ORM\Column(length: 32, unique: true, nullable: true)]
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
