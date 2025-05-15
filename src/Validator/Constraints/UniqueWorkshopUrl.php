<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueWorkshopUrl extends Constraint
{
    public string $message = 'Warsztat o podanej nazwie już istnieje w wybranym mieście. Proszę zmienić nazwę warsztatu lub wybrać inne miasto.';
    
    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }
}
