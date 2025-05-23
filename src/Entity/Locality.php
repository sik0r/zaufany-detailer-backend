<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LocalityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Table(name: 'locality')]
#[ORM\Entity(repositoryClass: LocalityRepository::class)]
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

    #[ORM\Column(length: 255)]
    private string $slug;

    #[ORM\Column(length: 50, unique: true)]
    private string $externalId;

    #[ORM\Column(length: 2)]
    private string $typeCode; // RM code from CSV

    /**
     * @var array<string, mixed>
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $externalData = [];

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $externalUpdatedDay;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $updatedAt;

    /**
     * @param array<string, mixed> $externalData
     */
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

    /**
     * @return array<string, mixed>
     */
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

    /**
     * @param array<string, mixed> $externalData
     */
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
