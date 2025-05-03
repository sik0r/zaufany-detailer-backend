<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/panel-warsztatu')]
#[IsGranted('ROLE_WORKSHOP')]
class WorkshopPanelController extends AbstractController
{
    #[Route('', name: 'workshop_panel_dashboard', methods: ['GET'])]
    public function dashboard(): Response
    {
        return $this->render('workshop_panel/dashboard.html.twig');
    }
}
