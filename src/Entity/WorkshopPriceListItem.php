<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'workshop_price_list_item')]
class WorkshopPriceListItem
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Workshop::class, inversedBy: 'priceListItems')]
    #[ORM\JoinColumn(nullable: false)]
    private Workshop $workshop;

    #[ORM\ManyToOne(targetEntity: Service::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Service $service;

    #[ORM\Column(type: 'string')]
    private string $price;

    #[ORM\Column(type: 'text', length: 255, nullable: true)]
    private ?string $description = null;

    public function __construct(
        Workshop $workshop,
        Service $service,
        string $price,
        ?string $description = null,
    ) {
        $this->id = Uuid::v7();
        $this->workshop = $workshop;
        $this->service = $service;
        $this->price = $price;
        $this->description = $description;
    }

    // Getters & setters
    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getWorkshop(): Workshop
    {
        return $this->workshop;
    }

    public function setWorkshop(Workshop $workshop): self
    {
        $this->workshop = $workshop;

        return $this;
    }

    public function getService(): Service
    {
        return $this->service;
    }

    public function setService(Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
