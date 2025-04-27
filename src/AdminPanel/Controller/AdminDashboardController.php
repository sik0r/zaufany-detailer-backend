<?php

declare(strict_types=1);

namespace App\AdminPanel\Controller;

use App\Repository\CompanyRegisterLeadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // Or Attribute

class AdminDashboardController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(CompanyRegisterLeadRepository $leadRepository): Response
    {
        // Fetch leads with status 'new' (or adjust status as needed)
        $newLeads = $leadRepository->findBy(['status' => 'new'], ['createdAt' => 'DESC']);

        return $this->render('admin_panel/dashboard.html.twig', [
            'leads' => $newLeads,
        ]);
    }
}
