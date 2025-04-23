<?php

declare(strict_types=1);

namespace App\Dto;

class CompanyRegisterLeadDto
{
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?string $nip = null;
    public ?string $phoneNumber = null;
    public ?string $email = null;
} 