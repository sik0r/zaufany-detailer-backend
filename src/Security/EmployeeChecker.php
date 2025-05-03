<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Employee;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class EmployeeChecker implements UserCheckerInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof Employee) {
            return;
        }

        if (!$user->isActive()) {
            $this->logger->warning(
                sprintf('Nieudana próba logowania na nieaktywne konto: %s', $user->getUserIdentifier())
            );
            throw new CustomUserMessageAccountStatusException('Twoje konto jest nieaktywne. Skontaktuj się z administratorem.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // No post-auth checks needed for this requirement
    }
} 