<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class RegonValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof Regon) {
            throw new UnexpectedTypeException($constraint, Regon::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        // Remove any non-digit characters
        $cleanedValue = preg_replace('/[^\d]/', '', $value);

        if (!ctype_digit($cleanedValue)) {
             $this->context->buildViolation($constraint->invalidFormatMessage)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
            return;
        }

        $length = strlen($cleanedValue);
        if ($length !== 9 && $length !== 14) {
            $this->context->buildViolation($constraint->invalidLengthMessage)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
            return;
        }

        // Basic checksum validation could be added here later
        // For now, just checking format and length.
    }
} 