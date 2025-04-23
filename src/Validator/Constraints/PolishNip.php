<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class PolishNip extends Constraint
{
    public string $message = 'Podany NIP "{{ value }}" jest niepoprawny.';
} 