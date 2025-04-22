<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Nip extends Constraint
{
    public string $message = 'Podany NIP "{{ value }}" jest nieprawidłowy.';
}
