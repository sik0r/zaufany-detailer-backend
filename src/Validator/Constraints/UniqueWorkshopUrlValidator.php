<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Dto\Workshop\CreateWorkshopDto;
use App\Repository\UrlWorkshopRepository;
use App\Service\Workshop\UrlWorkshopService;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueWorkshopUrlValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UrlWorkshopRepository $urlWorkshopRepository,
        private readonly UrlWorkshopService $urlWorkshopService,
        private readonly SluggerInterface $slugger
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueWorkshopUrl) {
            throw new UnexpectedTypeException($constraint, UniqueWorkshopUrl::class);
        }

        if (!$value instanceof CreateWorkshopDto) {
            throw new UnexpectedTypeException($value, CreateWorkshopDto::class);
        }

        if (!isset($value->name) || !isset($value->city) || !isset($value->region)) {
            return;
        }

        $workshopSlug = $this->slugger->slug($value->name)->lower()->toString();
        $url = $this->urlWorkshopService->generateUrl($value->region->getSlug(), $value->city->getSlug(), $workshopSlug);

        $existingUrl = $this->urlWorkshopRepository->findOneBy(['url' => $url]);

        if ($existingUrl !== null) {
            $this->context->buildViolation($constraint->message)
                ->atPath('name')
                ->addViolation();
        }
    }
}
