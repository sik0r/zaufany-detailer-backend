<?php

declare(strict_types=1);

namespace App\WorkshopPanel\Dto;

use App\Validator\Nip;
use App\Validator\Regon;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterCompanyRequestDto
{
    #[Assert\NotBlank(message: 'Nazwa firmy jest wymagana.')]
    public ?string $companyName = null;

    #[Assert\NotBlank(message: 'Imię jest wymagane.')]
    public ?string $employeeFirstName = null;

    #[Assert\NotBlank(message: 'Nazwisko jest wymagane.')]
    public ?string $employeeLastName = null;

    #[Assert\NotBlank(message: 'NIP jest wymagany.')]
    #[Assert\Length(exactly: 10, exactMessage: 'NIP musi składać się z 10 cyfr.')]
    #[Nip]
    public ?string $nip = null;

    #[Assert\NotBlank(message: 'REGON jest wymagany.')]
    #[Assert\Length(min: 9, max: 14, minMessage: 'REGON musi mieć co najmniej 9 cyfr.', maxMessage: 'REGON może mieć maksymalnie 14 cyfr.')]
    #[Regon]
    public ?string $regon = null;

    #[Assert\NotBlank(message: 'Adres e-mail jest wymagany.')]
    #[Assert\Email(message: 'Podaj poprawny adres e-mail.')]
    // Uniqueness will be checked in the service
    public ?string $email = null;

    #[Assert\NotBlank(message: 'Numer telefonu jest wymagany.')]
    // Basic validation, more specific can be added if needed
    public ?string $phoneNumber = null;

    #[Assert\NotBlank(message: 'Ulica jest wymagana.')]
    public ?string $companyStreet = null;

    #[Assert\NotBlank(message: 'Kod pocztowy jest wymagany.')]
    #[Assert\Regex(pattern: '/^\d{2}-\d{3}$/', message: 'Podaj poprawny kod pocztowy (np. 00-000).')]
    public ?string $companyPostalCode = null;

    #[Assert\NotBlank(message: 'Miasto jest wymagane.')]
    public ?string $companyCity = null;

    #[Assert\NotBlank(message: 'Hasło jest wymagane.')]
    #[Assert\Length(min: 8, minMessage: 'Hasło musi mieć co najmniej 8 znaków.')]
    public ?string $password = null;

    #[Assert\NotBlank(message: 'Potwierdzenie hasła jest wymagane.')]
    #[Assert\EqualTo(propertyPath: 'password', message: 'Hasła muszą być identyczne.')]
    public ?string $passwordConfirm = null;

    #[Assert\IsTrue(message: 'Musisz zaakceptować regulamin.')]
    public bool $termsAccepted = false;
} 