<?php

declare(strict_types=1);

namespace App\Controller\WorkshopPanel;

use App\Dto\PaginatedListRequest;
use App\Entity\Employee;
use App\Form\WorkshopCreateType;
use App\Service\Workshop\WorkshopService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/panel-warsztatu/warsztaty', name: 'workshop_panel_workshop_')]
class WorkshopController extends AbstractController
{
    public function __construct(
        private readonly WorkshopService $workshopService,
    ) {}

    #[Route(path: '/', name: 'index')]
    public function index(Request $request): Response
    {
        /** @var Employee $user */
        $user = $this->getUser();

        $pagination = $this->workshopService->getPaginatedList(
            $user,
            PaginatedListRequest::ofRequest($request),
        );

        return $this->render('workshop_panel/workshop/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route(path: '/new', name: 'new')]
    public function new(Request $request): Response
    {
        /** @var Employee $user */
        $user = $this->getUser();
        $company = $user->getCompany();

        $form = $this->createForm(WorkshopCreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->workshopService->createWorkshop($form->getData(), $company);
            $this->addFlash('success', 'Warsztat utworzony');

            return $this->redirectToRoute('workshop_panel_workshop_index');
        }

        return $this->render('workshop_panel/workshop/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
