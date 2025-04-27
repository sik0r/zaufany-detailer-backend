<?php

declare(strict_types=1);

namespace App\AdminPanel\Command;

use App\AdminPanel\Entity\Admin;
use App\AdminPanel\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Creates a new admin user.',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly AdminRepository $adminRepository,
        private readonly ValidatorInterface $validator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the admin.')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the admin.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        // Basic validation for email format (more robust validation is handled by the entity assertion)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $io->error('Invalid email format.');

            return Command::INVALID;
        }

        // Check if admin already exists
        $existingAdmin = $this->adminRepository->findOneBy(['email' => $email]);
        if ($existingAdmin) {
            $io->error(sprintf('Admin with email "%s" already exists.', $email));

            return Command::FAILURE;
        }

        $admin = new Admin();
        $admin->setEmail($email);
        $admin->setRoles(['ROLE_SUPER_ADMIN']); // Set the super admin role

        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword($admin, $password);
        $admin->setPassword($hashedPassword);

        // Validate the entity before persisting (optional but good practice)
        $errors = $this->validator->validate($admin);
        if (count($errors) > 0) {
            $io->error((string) $errors);

            return Command::FAILURE;
        }

        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $io->success(sprintf('Admin user "%s" created successfully.', $email));

        return Command::SUCCESS;
    }
}
