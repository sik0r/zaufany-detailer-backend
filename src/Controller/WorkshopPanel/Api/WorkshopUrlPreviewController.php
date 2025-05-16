<?php

declare(strict_types=1);

namespace App\Controller\WorkshopPanel\Api;

use App\Entity\Locality;
use App\Entity\Voivodeship;
use App\Repository\LocalityRepository;
use App\Repository\VoivodeshipRepository;
use App\Service\Workshop\UrlWorkshopService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_USER')]
#[Route(path: '/api', name: 'api_')]
class WorkshopUrlPreviewController extends AbstractController
{
    private const string URL_PREFIX = 'auto-detailing';

    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly LocalityRepository $localityRepository,
        private readonly VoivodeshipRepository $voivodeshipRepository,
        private readonly UrlWorkshopService $urlWorkshopService
    ) {}

    #[Route(path: '/workshop-url-preview', name: 'workshop_url_preview', methods: ['GET'])]
    public function getPreview(Request $request): JsonResponse
    {
        $name = $request->query->get('name');
        $regionId = $request->query->get('region');
        $cityId = $request->query->get('city');

        if (!$name || !$regionId || !$cityId) {
            return $this->json([
                'url' => null,
                'message' => 'Uzupełnij nazwę warsztatu, województwo i miasto',
            ]);
        }

        $region = $this->voivodeshipRepository->find($regionId);
        $city = $this->localityRepository->find($cityId);

        if (!$region instanceof Voivodeship || !$city instanceof Locality) {
            return $this->json([
                'url' => null,
                'message' => 'Nieprawidłowe województwo lub miasto',
            ]);
        }

        $workshopSlug = $this->slugger->slug($name)->lower()->toString();
        $workshopUrl = $this->urlWorkshopService->generateUrl($region->getSlug(), $city->getSlug(), $workshopSlug);
        $url = sprintf(
            '/%s/%s',
            self::URL_PREFIX,
            $workshopUrl
        );

        return $this->json([
            'url' => $url,
            'workshopUrl' => $workshopUrl,
            'workshopSlug' => $workshopSlug,
            'message' => null,
        ]);
    }
}
