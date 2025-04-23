<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueDtoPropertyValidator extends ConstraintValidator
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueDtoProperty) {
            throw new UnexpectedTypeException($constraint, UniqueDtoProperty::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $entityClass = $constraint->entityClass;
        $entityField = $constraint->entityField;
        $dtoIdField = $constraint->dtoIdField;

        $repository = $this->entityManager->getRepository($entityClass);
        if (!$repository) {
            throw new ConstraintDefinitionException(sprintf('Unable to find the repository for class "%s".', $entityClass));
        }

        $dto = $this->context->getObject();
        if (null === $dto) {
             // Should not happen in property validation context, but good practice to check
            return;
        }

        $excludeId = null;
        if ($dtoIdField && property_exists($dto, $dtoIdField)) {
            $excludeId = $dto->{$dtoIdField};
            // Optional: Validate if $excludeId is a valid type (e.g., Uuid) if necessary
        }

        // Use QueryBuilder for more flexibility in excluding the ID
        $qb = $repository->createQueryBuilder('e');
        $qb->select('COUNT(e.id)')
            ->where(sprintf('e.%s = :value', $entityField))
            ->setParameter('value', $value);

        if ($excludeId !== null) {
            // Assuming the ID field in the entity is always 'id'
            $qb->andWhere('e.id != :excludeId')
               ->setParameter('excludeId', $excludeId);
        }

        $count = (int) $qb->getQuery()->getSingleScalarResult();

        if ($count > 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value, self::OBJECT_TO_STRING))
                ->addViolation();
        }
    }
} 