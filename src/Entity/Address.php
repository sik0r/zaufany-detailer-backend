<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Table(name: 'address')]
#[ORM\Entity]
#[ORM\Index(name: 'address_region_city_idx', columns: ['region_name', 'city_name'])]
class Address
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $street;

    #[ORM\Column(type: 'string', length: 6)]
    private string $postalCode;

    #[ORM\ManyToOne(targetEntity: Voivodeship::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Voivodeship $region;

    #[ORM\ManyToOne(targetEntity: Locality::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Locality $city;

    #[ORM\Column(type: 'string', length: 100)]
    private string $regionName;

    #[ORM\Column(type: 'string', length: 120)]
    private string $regionSlug;

    #[ORM\Column(type: 'string', length: 255)]
    private string $cityName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $citySlug;

    public function __construct(
        Uuid $id,
        string $street,
        string $postalCode,
        Voivodeship $region,
        Locality $city,
        string $regionName,
        string $regionSlug,
        string $cityName,
        string $citySlug
    ) {
        $this->id = $id;
        $this->street = $street;
        $this->postalCode = $postalCode;
        $this->region = $region;
        $this->city = $city;
        $this->regionName = $regionName;
        $this->regionSlug = $regionSlug;
        $this->cityName = $cityName;
        $this->citySlug = $citySlug;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getRegion(): Voivodeship
    {
        return $this->region;
    }

    public function getCity(): Locality
    {
        return $this->city;
    }

    public function getRegionName(): string
    {
        return $this->regionName;
    }

    public function getRegionSlug(): string
    {
        return $this->regionSlug;
    }

    public function getCityName(): string
    {
        return $this->cityName;
    }

    public function getCitySlug(): string
    {
        return $this->citySlug;
    }
}
