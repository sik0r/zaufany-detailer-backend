<?php

declare(strict_types=1);

namespace App\Controller\WorkshopPanel\Api;

use App\Repository\LocalityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route(path: '/api', name: 'api_')]
class LocalityController extends AbstractController
{
    public function __construct(
        private readonly LocalityRepository $localityRepository,
    ) {}

    #[Route('/localities', name: 'localities_by_region', methods: ['GET'])]
    public function getByRegion(Request $request): JsonResponse
    {
        $regionId = $request->query->get('region');
        if (!$regionId) {
            return new JsonResponse([]);
        }

        $cities = $this->localityRepository->findBy(['voivodeship' => $regionId]);

        return new JsonResponse(
            array_map(
                fn ($city) => [
                    'id' => $city->getId(),
                    'name' => $city->getName(),
                ],
                $cities
            )
        );
    }
}
