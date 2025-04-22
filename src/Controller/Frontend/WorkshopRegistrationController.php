<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Service\Workshop\CompanyRegistrationService;
use App\Service\Workshop\Exception\DuplicateEmailException;
use App\Service\Workshop\Exception\DuplicateNipException;
use App\WorkshopPanel\Dto\RegisterCompanyRequestDto;
use App\WorkshopPanel\Form\RegistrationFormType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dla-warsztatow')]
class WorkshopRegistrationController extends AbstractController
{
    #[Route('/zaloz-konto', name: 'workshop_register_form', methods: ['GET'])]
    public function showForm(): Response
    {
        $form = $this->createForm(RegistrationFormType::class, new RegisterCompanyRequestDto());

        return $this->render('frontend/dla_warsztatow/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/zaloz-konto', name: 'workshop_register_handle', methods: ['POST'])]
    public function handleRegistration(
        Request $request,
        CompanyRegistrationService $registrationService,
        LoggerInterface $logger
    ): Response {
        $dto = new RegisterCompanyRequestDto();
        $form = $this->createForm(RegistrationFormType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $registrationService->registerCompany($dto);
                // Use a more persistent flash type if needed, but session flash is typical
                $this->addFlash('success', 'Rejestracja przebiegła pomyślnie! Twoje konto oczekuje na aktywację przez administratora.');

                return $this->redirectToRoute('workshop_register_thankyou');
            } catch (DuplicateNipException | DuplicateEmailException $e) {
                $this->addFlash('danger', $e->getMessage());
                 $logger->warning(sprintf('Registration failed: %s', $e->getMessage()), ['exception' => $e]);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Wystąpił nieoczekiwany błąd podczas rejestracji. Spróbuj ponownie.');
                $logger->error(sprintf('Unexpected registration error: %s', $e->getMessage()), ['exception' => $e]);
            }
        }

        // If form is not valid or an exception occurred, re-render the form
        return $this->render('frontend/dla_warsztatow/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/zaloz-konto/dziekujemy', name: 'workshop_register_thankyou', methods: ['GET'])]
    public function showThankYou(Request $request): Response
    {
        // Check if the success flash message exists, otherwise redirect (prevents direct access)
        // Accessing session requires enabling it or using request->getSession()
        if (!$request->getSession()->getFlashBag()->has('success')) {
             return $this->redirectToRoute('workshop_register_form');
        }

        return $this->render('frontend/dla_warsztatow/thank_you.html.twig');
    }
}
