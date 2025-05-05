<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PolishNipValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof PolishNip) {
            throw new UnexpectedTypeException($constraint, PolishNip::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!$this->isValid($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation()
            ;
        }
    }

    /**
     * Logika walidacji NIP zaczerpnięta z kiczort/polish-validator.
     */
    private function isValid(string $value): bool
    {
        if (!preg_match('/^\d{10}$/', $value) || '0000000000' == $value) {
            return false;
        }

        $chars = str_split($value);
        $sum = array_sum(array_map(function ($weight, $digit) {
            return $weight * (int) $digit;
        }, [6, 5, 7, 2, 3, 4, 5, 6, 7], array_slice($chars, 0, 9)));

        $checksum = $sum % 11;

        return $checksum == $chars[9];
    }
}
