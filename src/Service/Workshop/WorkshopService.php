<?php

declare(strict_types=1);

namespace App\Service\Workshop;

use App\Dto\PaginatedListRequest;
use App\Dto\Workshop\CreateWorkshopDto;
use App\Entity\Address;
use App\Entity\Company;
use App\Entity\Employee;
use App\Entity\Workshop;
use App\Repository\WorkshopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Uid\Uuid;

readonly class WorkshopService
{
    public function __construct(
        private WorkshopRepository $workshopRepository,
        private PaginatorInterface $paginator,
        private EntityManagerInterface $entityManager,
        private UrlWorkshopService $urlWorkshopService
    ) {}

    /** @return PaginationInterface<int, Workshop> */
    public function getPaginatedList(Employee $employee, PaginatedListRequest $request): PaginationInterface
    {
        $queryBuilder = $this->workshopRepository->paginatedList($employee->getCompany());

        return $this->paginator->paginate(
            $queryBuilder,
            $request->page,
            $request->limit
        );
    }

    public function createWorkshop(CreateWorkshopDto $dto, Company $company): Workshop
    {
        $this->entityManager->beginTransaction();

        try {
            $address = new Address(
                Uuid::v7(),
                $dto->street,
                $dto->postalCode,
                $dto->region,
                $dto->city,
            );

            $slugger = new AsciiSlugger('pl');
            $slug = $slugger->slug($dto->name)->lower()->toString();

            $workshop = new Workshop(Uuid::v7(), $company, $slug);
            $workshop->setName($dto->name)
                ->setEmail($dto->email)
                ->setAddress($address)
            ;

            $this->urlWorkshopService->createUrlForWorkshop($workshop);

            $this->entityManager->persist($address);
            $this->entityManager->persist($workshop);
            $this->entityManager->flush();

            $this->entityManager->commit();

            return $workshop;
        } catch (\Throwable $t) {
            $this->entityManager->rollback();

            throw $t;
        }
    }
}
