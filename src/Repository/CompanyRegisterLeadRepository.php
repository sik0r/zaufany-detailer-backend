<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CompanyRegisterLead;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompanyRegisterLead>
 */
class CompanyRegisterLeadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyRegisterLead::class);
    }

    public function save(CompanyRegisterLead $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CompanyRegisterLead $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByNip(string $nip): ?CompanyRegisterLead
    {
        return $this->findOneBy(['nip' => $nip]);
    }

    public function findOneByEmail(string $email): ?CompanyRegisterLead
    {
        return $this->findOneBy(['email' => $email]);
    }
}
