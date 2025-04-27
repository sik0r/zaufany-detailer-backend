<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Sprawdza, czy wartość pola w DTO jest unikalna w kolumnie encji w bazie danych.
 * Obsługuje scenariusze tworzenia i edycji (poprzez opcjonalne wykluczenie ID).
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class UniqueDtoProperty extends Constraint
{
    public string $message = 'Ta wartość jest już używana.';
    public string $entityClass; // FQCN encji do sprawdzenia
    public string $entityField; // Nazwa pola w encji do sprawdzenia
    public ?string $dtoIdField = null; // Opcjonalna nazwa pola w DTO przechowującego ID encji do wykluczenia

    public function __construct(
        string $entityClass,
        string $entityField,
        ?string $dtoIdField = null,
        ?string $message = null,
        ?array $groups = null,
        $payload = null,
        array $options = []
    ) {
        $this->entityClass = $entityClass;
        $this->entityField = $entityField;
        $this->dtoIdField = $dtoIdField;
        $options['entityClass'] = $this->entityClass;
        $options['entityField'] = $this->entityField;
        $options['dtoIdField'] = $this->dtoIdField;

        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
    }

    public function getRequiredOptions(): array
    {
        return ['entityClass', 'entityField'];
    }

    public function validatedBy(): string
    {
        return static::class.'Validator';
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
