<?php

declare(strict_types=1);

namespace App\Service\Workshop;

use App\Entity\UrlWorkshop;
use App\Entity\Workshop;
use App\Repository\UrlWorkshopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

readonly class UrlWorkshopService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UrlWorkshopRepository $urlWorkshopRepository,
    ) {
    }

    public function createUrlForWorkshop(Workshop $workshop): UrlWorkshop
    {
        $url = $this->buildUrlPath($workshop);

        $urlWorkshop = new UrlWorkshop(
            Uuid::v7(),
            $workshop,
            $url
        );

        $workshop->setUrlWorkshop($urlWorkshop);
        $this->entityManager->persist($urlWorkshop);

        return $urlWorkshop;
    }

    public function generateUrl(string $region, string $city, string $slug): string
    {
        return sprintf('%s/%s/%s', $region, $city, $slug);
    }

    public function findWorkshopByUrl(string $url): ?Workshop
    {
        $urlWorkshop = $this->urlWorkshopRepository->findOneBy(['url' => $url]);

        return $urlWorkshop?->getWorkshop();
    }

    private function buildUrlPath(Workshop $workshop): string
    {
        $address = $workshop->getAddress();

        return $this->generateUrl(
            $address->getRegion()->getSlug(),
            $address->getCity()->getSlug(),
            $workshop->getSlug()
        );
    }
}
