<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VoivodeshipRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Table(name: 'voivodeship')]
#[ORM\Entity(repositoryClass: VoivodeshipRepository::class)]
class Voivodeship
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private Uuid $id;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(length: 120, unique: true)]
    private string $slug;

    #[ORM\Column(length: 50, unique: true)]
    private string $externalId;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $externalUpdatedDay;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        Uuid $id,
        string $name,
        string $slug,
        string $externalId,
        \DateTimeImmutable $externalUpdatedDay,
        ?\DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->externalId = $externalId;
        $this->externalUpdatedDay = $externalUpdatedDay;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getExternalUpdatedDay(): \DateTimeInterface
    {
        return $this->externalUpdatedDay;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function update(
        string $name,
        string $slug,
        string $externalId,
        \DateTimeImmutable $updateDate,
        \DateTimeImmutable $updatedAt
    ): void {
        $this->name = $name;
        $this->slug = $slug;
        $this->externalId = $externalId;
        $this->externalUpdatedDay = $updateDate;
        $this->updatedAt = $updatedAt;
    }
}
