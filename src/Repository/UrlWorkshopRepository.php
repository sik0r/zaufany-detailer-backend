<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UrlWorkshop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UrlWorkshop>
 */
class UrlWorkshopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UrlWorkshop::class);
    }
}
