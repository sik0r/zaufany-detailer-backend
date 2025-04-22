<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Regon extends Constraint
{
    public string $invalidLengthMessage = 'REGON musi składać się z 9 lub 14 cyfr.';
    public string $invalidFormatMessage = 'REGON może zawierać tylko cyfry.';
    // Add checksum message later if implementing checksum validation
    // public string $checksumMessage = 'Podany REGON ma nieprawidłową sumę kontrolną.';
} 