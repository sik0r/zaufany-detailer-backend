<?php

declare(strict_types=1);

namespace App\Service\Workshop;

use App\Entity\Company;
use App\Entity\Employee;
use App\Repository\CompanyRepository;
use App\Repository\EmployeeRepository;
use App\Service\Workshop\Exception\DuplicateEmailException;
use App\Service\Workshop\Exception\DuplicateNipException;
use App\WorkshopPanel\Dto\RegisterCompanyRequestDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class CompanyRegistrationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private EmployeeRepository $employeeRepository,
        private CompanyRepository $companyRepository
    ) {}

    /**
     * @throws DuplicateNipException
     * @throws DuplicateEmailException
     */
    public function registerCompany(RegisterCompanyRequestDto $dto): void
    {
        // Clean NIP and REGON just in case
        $cleanNip = preg_replace('/[^\d]/', '', $dto->nip);
        $cleanRegon = preg_replace('/[^\d]/', '', $dto->regon);

        // Check for existing NIP
        if ($this->companyRepository->findOneByNip($cleanNip)) {
            throw new DuplicateNipException($cleanNip);
        }

        // Check for existing Email
        if ($this->employeeRepository->findOneByEmail($dto->email)) {
            throw new DuplicateEmailException($dto->email);
        }

        $company = new Company();
        $company->setName($dto->companyName);
        $company->setNip($cleanNip);
        $company->setRegon($cleanRegon);
        $company->setAddressStreet($dto->companyStreet);
        $company->setAddressPostalCode($dto->companyPostalCode);
        $company->setAddressCity($dto->companyCity);

        $employee = new Employee();
        $employee->setEmail($dto->email);
        $employee->setFirstName($dto->employeeFirstName);
        $employee->setLastName($dto->employeeLastName);
        $employee->setPhoneNumber($dto->phoneNumber);
        $employee->setIsActive(false); // Account is inactive by default
        $employee->setCompany($company); // Associate employee with the new company

        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword(
            $employee, // User object
            $dto->password // Plain password from DTO
        );
        $employee->setPassword($hashedPassword);

        // Set the owner relationship
        $company->setOwner($employee);

        $this->entityManager->persist($company);
        // Employee is persisted via cascade from Company owner relationship
        // $this->entityManager->persist($employee);
        $this->entityManager->flush();
    }
} 