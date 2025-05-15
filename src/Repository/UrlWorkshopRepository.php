<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UrlWorkshop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UrlWorkshop>
 *
 * @method UrlWorkshop|null find($id, $lockMode = null, $lockVersion = null)
 * @method UrlWorkshop|null findOneBy(array $criteria, array $orderBy = null)
 * @method UrlWorkshop[]    findAll()
 * @method UrlWorkshop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlWorkshopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UrlWorkshop::class);
    }
}
