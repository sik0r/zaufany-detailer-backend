<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Voivodeship;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Voivodeship>
 *
 * @method null|Voivodeship find($id, $lockMode = null, $lockVersion = null)
 * @method null|Voivodeship findOneBy(array<string, mixed> $criteria, array<string, string> $orderBy = null)
 * @method Voivodeship[]    findAll()
 * @method Voivodeship[]    findBy(array<string, mixed> $criteria, array<string, string> $orderBy = null, $limit = null, $offset = null)
 */
class VoivodeshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voivodeship::class);
    }

    public function findByExternalId(string $externalId): ?Voivodeship
    {
        return $this->findOneBy(['externalId' => $externalId]);
    }
}
