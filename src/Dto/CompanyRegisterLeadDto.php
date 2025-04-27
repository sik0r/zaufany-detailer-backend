<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\CompanyRegisterLead;
use App\Validator\Constraints\PolishNip;
use App\Validator\Constraints\UniqueDtoProperty;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyRegisterLeadDto
{
    #[Assert\NotBlank(message: 'Imię jest wymagane.')]
    #[Assert\Length(max: 255, maxMessage: 'Imię nie może przekraczać {{ limit }} znaków.')]
    public ?string $firstName = null;

    #[Assert\NotBlank(message: 'Nazwisko jest wymagane.')]
    #[Assert\Length(max: 255, maxMessage: 'Nazwisko nie może przekraczać {{ limit }} znaków.')]
    public ?string $lastName = null;

    #[Assert\NotBlank(message: 'NIP jest wymagany.')]
    #[Assert\Length(max: 20, maxMessage: 'NIP nie może przekraczać {{ limit }} znaków.')]
    #[PolishNip]
    #[UniqueDtoProperty(entityClass: CompanyRegisterLead::class, entityField: 'nip', message: 'Podany NIP jest już zarejestrowany.')]
    public ?string $nip = null;

    #[Assert\NotBlank(message: 'Numer telefonu jest wymagany.')]
    #[Assert\Length(max: 50, maxMessage: 'Numer telefonu nie może przekraczać {{ limit }} znaków.')]
    public ?string $phoneNumber = null;

    #[Assert\NotBlank(message: 'Email jest wymagany.')]
    #[Assert\Email(message: 'Podany adres e-mail jest nieprawidłowy.')]
    #[Assert\Length(max: 255, maxMessage: 'Email nie może przekraczać {{ limit }} znaków.')]
    #[UniqueDtoProperty(entityClass: CompanyRegisterLead::class, entityField: 'email', message: 'Podany adres e-mail jest już zarejestrowany.')]
    public ?string $email = null;
}
