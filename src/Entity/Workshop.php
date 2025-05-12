<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'workshop')]
class Workshop
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private Uuid $id;

    #[ORM\Column(length: 240)]
    private string $name;

    #[ORM\Column(length: 255, unique: true)]
    private string $slug;

    #[ORM\Column(type: 'text', length: 2000, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToOne(targetEntity: Address::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Address $address;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\ManyToMany(targetEntity: Service::class)]
    #[ORM\JoinTable(name: 'workshop_service')]
    #[ORM\JoinColumn(name: 'service_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Collection $services;

    /**
     * @var array<string>
     */
    #[ORM\Column(type: 'json')]
    private array $openingHours = [];

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private string $email;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    /**
     * @var array<string>
     */
    #[ORM\Column(type: 'json')]
    private array $gallery = [];

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Company $company;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isPublished = false;

    #[ORM\OneToOne(targetEntity: WorkshopMeta::class, mappedBy: 'workshop', cascade: [
        'persist',
        'remove',
    ])]
    private ?WorkshopMeta $meta = null;

    /**
     * @var Collection<int, WorkshopPriceListItem>
     */
    #[ORM\OneToMany(targetEntity: WorkshopPriceListItem::class, mappedBy: 'workshop', cascade: [
        'persist',
        'remove',
    ])]
    private Collection $priceListItems;

    #[ORM\Column(type: 'datetimetz_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetimetz_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->id = Uuid::v7();
        $this->services = new ArrayCollection();
        $this->priceListItems = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): self
    {
        $this->company = $company;

        return $this;
    }
}
