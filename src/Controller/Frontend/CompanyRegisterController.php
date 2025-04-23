<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Dto\CompanyRegisterLeadDto;
use App\Entity\CompanyRegisterLead;
use App\Form\CompanyRegisterLeadType;
use App\Repository\CompanyRegisterLeadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class CompanyRegisterController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CompanyRegisterLeadRepository $companyRegisterLeadRepository,
        private readonly MailerInterface $mailer
    ) {
    }

    #[Route('/dla-warsztatow/zaloz-konto', name: 'frontend_company_register', methods: ['GET', 'POST'])]
    public function register(Request $request): Response
    {
        $companyRegisterLeadDto = new CompanyRegisterLeadDto();
        $form = $this->createForm(CompanyRegisterLeadType::class, $companyRegisterLeadDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Walidacja unikalności NIP
            $existingNip = $this->companyRegisterLeadRepository->findOneByNip($companyRegisterLeadDto->nip);
            if ($existingNip) {
                $form->get('nip')->addError(new FormError('Podany NIP jest już zarejestrowany.'));
            }

            // Walidacja unikalności Email
            $existingEmail = $this->companyRegisterLeadRepository->findOneByEmail($companyRegisterLeadDto->email);
            if ($existingEmail) {
                $form->get('email')->addError(new FormError('Podany adres e-mail jest już zarejestrowany.'));
            }

            // Jeśli są błędy, przerwij przetwarzanie
            if (!$form->isValid()) {
                return $this->render('frontend/dla_warsztatow/register.html.twig', [
                    'registrationForm' => $form->createView(),
                ]);
            }

            // Tworzenie i zapis encji
            $lead = new CompanyRegisterLead();
            $lead->setFirstName($companyRegisterLeadDto->firstName);
            $lead->setLastName($companyRegisterLeadDto->lastName);
            $lead->setNip($companyRegisterLeadDto->nip);
            $lead->setPhoneNumber($companyRegisterLeadDto->phoneNumber);
            $lead->setEmail($companyRegisterLeadDto->email);
            $lead->setStatus('new');

            $this->entityManager->persist($lead);
            $this->entityManager->flush();

            // Wysłanie emaila potwierdzającego
            $email = (new Email())
                ->from('noreply@zaufany-detailer.pl')
                ->to($companyRegisterLeadDto->email)
                ->subject('Potwierdzenie zgłoszenia - Zaufany Detailer')
                ->html($this->renderView('emails/company_register_confirmation.html.twig', [
                    'leadDto' => $companyRegisterLeadDto
                ]));

            $this->mailer->send($email);

            // Komunikat flash i przekierowanie
            $this->addFlash('success', 'Dziękujemy za zgłoszenie! Wysłaliśmy potwierdzenie na Twój adres e-mail. Skontaktujemy się z Tobą telefonicznie w ciągu 48 godzin roboczych.');

            return $this->redirectToRoute('frontend_company_register');
        }

        return $this->render('frontend/dla_warsztatow/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
} 