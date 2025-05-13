<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Workshop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Workshop>
 */
class WorkshopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workshop::class);
    }

    public function paginatedList(Company $company): QueryBuilder
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.company = :company')
            ->setParameter('company', $company)
            ->orderBy('w.createdAt', 'DESC')
        ;
    }
}
