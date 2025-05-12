<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'workshop_meta')]
class WorkshopMeta
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private Uuid $id;

    #[ORM\OneToOne(targetEntity: Workshop::class, inversedBy: 'meta')]
    #[ORM\JoinColumn(nullable: false)]
    private Workshop $workshop;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metaTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metaDescription = null;

    public function __construct(Workshop $workshop)
    {
        $this->id = Uuid::v7();
        $this->workshop = $workshop;
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

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): self
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }
}
