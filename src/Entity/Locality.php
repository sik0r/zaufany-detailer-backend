<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LocalityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: LocalityRepository::class)]
#[ORM\Table(name: 'localities')]
#[ORM\UniqueConstraint(name: 'locality_external_id_idx', columns: ['external_id'])]
class Locality
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Voivodeship::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Voivodeship $voivodeship;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(length: 120)]
    private string $slug;

    #[ORM\Column(length: 50)]
    private string $externalId;

    #[ORM\Column(length: 2)]
    private string $typeCode; // RM code from CSV

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $externalData = [];

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $externalUpdatedDay;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        Uuid $id,
        Voivodeship $voivodeship,
        string $name,
        string $slug,
        string $externalId,
        string $typeCode,
        array $externalData,
        \DateTimeImmutable $externalUpdatedDay,
        \DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->voivodeship = $voivodeship;
        $this->name = $name;
        $this->slug = $slug;
        $this->externalId = $externalId;
        $this->typeCode = $typeCode;
        $this->externalData = $externalData;
        $this->externalUpdatedDay = $externalUpdatedDay;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getVoivodeship(): Voivodeship
    {
        return $this->voivodeship;
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

    public function getTypeCode(): string
    {
        return $this->typeCode;
    }

    public function getExternalData(): array
    {
        return $this->externalData;
    }

    public function getExternalUpdatedDay(): \DateTimeImmutable
    {
        return $this->externalUpdatedDay;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function update(
        Voivodeship $voivodeship,
        string $name,
        string $slug,
        string $externalId,
        string $typeCode,
        array $externalData,
        \DateTimeImmutable $externalUpdatedDay,
        \DateTimeImmutable $updatedAt
    ): void {
        $this->voivodeship = $voivodeship;
        $this->name = $name;
        $this->slug = $slug;
        $this->externalId = $externalId;
        $this->typeCode = $typeCode;
        $this->externalData = $externalData;
        $this->externalUpdatedDay = $externalUpdatedDay;
        $this->updatedAt = $updatedAt;
    }
}
