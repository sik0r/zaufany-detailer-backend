<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\Voivodeship;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Uid\Uuid;

class Fixtures
{
    private AsciiSlugger $slugger;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        $this->slugger = new AsciiSlugger('pl');
    }

    public function voivodeship(string $name): Voivodeship
    {
        $voivodeship = new Voivodeship(
            Uuid::v7(),
            $name,
            $this->slugger->slug($name)->toString(),
            uniqid(),
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );

        $this->entityManager->persist($voivodeship);
        $this->entityManager->flush();

        return $voivodeship;
    }
}
