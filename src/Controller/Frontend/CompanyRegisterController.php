<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Dto\CompanyRegisterLeadDto;
use App\Entity\CompanyRegisterLead;
use App\Form\CompanyRegisterLeadType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class CompanyRegisterController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MailerInterface $mailer,
        private readonly LoggerInterface $logger
    ) {
    }

    #[Route('/dla-warsztatow/zaloz-konto', name: 'frontend_company_register', methods: ['GET', 'POST'])]
    public function register(Request $request): Response
    {
        $companyRegisterLeadDto = new CompanyRegisterLeadDto();
        $form = $this->createForm(CompanyRegisterLeadType::class, $companyRegisterLeadDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lead = new CompanyRegisterLead();
            $lead->setFirstName($companyRegisterLeadDto->firstName);
            $lead->setLastName($companyRegisterLeadDto->lastName);
            $lead->setNip($companyRegisterLeadDto->nip);
            $lead->setPhoneNumber($companyRegisterLeadDto->phoneNumber);
            $lead->setEmail($companyRegisterLeadDto->email);

            $this->entityManager->persist($lead);
            $this->entityManager->flush();

            $email = (new Email())
                ->from('noreply@zaufany-detailer.pl') // Rozważ użycie parametru konfiguracyjnego
                ->to($companyRegisterLeadDto->email)
                ->subject('Potwierdzenie zgłoszenia - Zaufany Detailer')
                ->html($this->renderView('emails/company_register_confirmation.html.twig', [
                    'leadDto' => $companyRegisterLeadDto
                ]));

            try {
                $this->mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                $this->logger->critical($e->getMessage(), [
                    'exception' => $e,
                    'leadId' => $lead->getId()->toString(),
                ]);

                $this->addFlash('error', 'Wystąpił problem podczas wysyłania e-maila potwierdzającego. Twoje zgłoszenie zostało jednak zapisane.');
            }

            $this->addFlash('success', 'Dziękujemy za zgłoszenie! Wysłaliśmy potwierdzenie na Twój adres e-mail. Skontaktujemy się z Tobą telefonicznie w ciągu 48 godzin roboczych.');

            return $this->redirectToRoute('frontend_company_register');
        }

        return $this->render('frontend/dla_warsztatow/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
