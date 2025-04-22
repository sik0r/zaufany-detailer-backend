<?php

declare(strict_types=1);

namespace App\Validator;

use Ibericode\Vat\Validator as VatValidatorLib;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NipValidator extends ConstraintValidator
{
    private VatValidatorLib $vatValidator;

    public function __construct(?VatValidatorLib $vatValidator = null)
    {
        $this->vatValidator = $vatValidator ?? new VatValidatorLib();
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof Nip) {
            throw new UnexpectedTypeException($constraint, Nip::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        // Basic NIP format check (10 digits)
        if (!preg_match('/^\d{10}$/', $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
            return;
        }

        // Use ibericode/vat for checksum validation (assuming PL prefix implicitly for NIP)
        // The library expects a country code + VAT number.
        // We extract potential country code, but for polish NIP, we assume PL.
        // Note: ibericode/vat validator `validateVatNumberFormat` does more than just checksum,
        // it checks format based on country rules.
        // We only need the checksum part for a pure NIP (10 digits).
        // The library doesn't seem to have a direct method for just PL NIP checksum.
        // Let's try validating with 'PL' prefix.
        // Ensure the NIP doesn't already have PL prefix.
        $nipToCheck = $value;
        if (str_starts_with(strtoupper($nipToCheck), 'PL')) {
            $nipToCheck = substr($nipToCheck, 2);
        }

        // Check if the value consists only of digits after removing potential PL prefix
        if (!ctype_digit($nipToCheck) || strlen($nipToCheck) !== 10) {
             $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
            return;
        }

        // Attempt validation using ibericode/vat with 'PL' prefix for format/checksum
        if (!$this->vatValidator->validateVatNumberFormat('PL' . $nipToCheck)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }
    }
} 