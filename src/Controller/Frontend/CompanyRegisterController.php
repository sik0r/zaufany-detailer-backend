<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Dto\CompanyRegisterLeadDto;
use App\Form\CompanyRegisterLeadType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CompanyRegisterController extends AbstractController
{
    #[Route('/dla-warsztatow/zaloz-konto', name: 'frontend_company_register', methods: ['GET', 'POST'])]
    public function register(Request $request): Response
    {
        $companyRegisterLeadDto = new CompanyRegisterLeadDto();
        $form = $this->createForm(CompanyRegisterLeadType::class, $companyRegisterLeadDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO: Implement US-002 persistence logic here
            // - Validate NIP/Email uniqueness in CompanyRegisterLead
            // - Save CompanyRegisterLead entity
            // - Show success message
            // - Send confirmation email

            $this->addFlash('success', 'Dziękujemy za zgłoszenie! Skontaktujemy się z Tobą telefonicznie w ciągu 48 godzin roboczych w celu weryfikacji.');

            // For now, just redirect back to the form or a generic success page
            // In a real scenario, might redirect to a dedicated success page or the homepage
            return $this->redirectToRoute('frontend_company_register');
        }

        return $this->render('frontend/dla_warsztatow/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
} 