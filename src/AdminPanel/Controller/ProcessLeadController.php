<?php

declare(strict_types=1);

namespace App\AdminPanel\Controller;

use App\AdminPanel\Form\CompanyEmployeeType; // Will be created later
use App\Entity\Company;
use App\Entity\Employee;
use App\Repository\CompanyRegisterLeadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email; // Or Attribute
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ProcessLeadController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CompanyRegisterLeadRepository $leadRepository,
        private readonly MailerInterface $mailer,
        private readonly ?ResetPasswordHelperInterface $resetPasswordHelper, // Make nullable for initial check
        private readonly LoggerInterface $logger
    ) {}

    #[Route('/admin/process-lead/{id}', name: 'admin_process_lead')]
    public function process(Request $request, string $id): Response
    {
        if (null === $this->resetPasswordHelper) {
            throw new \LogicException('SymfonyCasts ResetPasswordBundle is not installed or configured. Please install and configure it to use this feature.');
        }

        $lead = $this->leadRepository->find($id);

        if (!$lead) {
            throw $this->createNotFoundException('Company Register Lead not found.');
        }

        // Optionally check lead status if needed (e.g., only process 'new' leads)
        if ('new' !== $lead->getStatus()) {
            $this->addFlash('warning', 'This lead has already been processed or is not in a processable state.');

            return $this->redirectToRoute('admin_dashboard');
        }

        $company = new Company();
        $employee = new Employee();

        // Pre-fill employee data from lead
        $employee->setEmail($lead->getEmail());
        $employee->setFirstName($lead->getFirstName());
        $employee->setLastName($lead->getLastName());
        $employee->setPhoneNumber($lead->getPhoneNumber());

        // Pre-fill company NIP from lead
        $company->setNip($lead->getNip());

        // Form data structure - map fields manually or use DTO
        $formData = [
            'company_name' => null,
            'company_nip' => $company->getNip(), // Pre-fill NIP
            'company_regon' => null,
            'company_street' => null,
            'company_postalCode' => null,
            'company_city' => null,
            'employee_firstName' => $employee->getFirstName(), // Pre-fill
            'employee_lastName' => $employee->getLastName(), // Pre-fill
            'employee_email' => $employee->getEmail(), // Pre-fill and likely readonly
            'employee_phoneNumber' => $employee->getPhoneNumber(), // Pre-fill
        ];

        $form = $this->createForm(CompanyEmployeeType::class, $formData); // Use form type created later

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submittedData = $form->getData();

            // Map data from $submittedData to Company and Employee entities
            $company->setName($submittedData['company_name']);
            // NIP is likely pre-filled/readonly, but handle if editable: $company->setNip($submittedData['company_nip']);
            $company->setRegon($submittedData['company_regon']);
            $company->setStreet($submittedData['company_street']);
            $company->setPostalCode($submittedData['company_postalCode']);
            $company->setCity($submittedData['company_city']);

            // Email is likely pre-filled/readonly: $employee->setEmail($submittedData['employee_email']);
            $employee->setFirstName($submittedData['employee_firstName']);
            $employee->setLastName($submittedData['employee_lastName']);
            $employee->setPhoneNumber($submittedData['employee_phoneNumber']);

            $employee->setIsActive(true);
            $employee->setCompany($company); // Associate employee with company
            $employee->setRoles(['ROLE_WORKSHOP']); // Assign default role

            // TODO: Add proper validation (e.g., using ValidatorInterface) before persisting

            $this->entityManager->persist($company);
            $this->entityManager->persist($employee);

            // Update lead status
            $lead->setStatus('processed'); // Or 'activated' or similar
            $this->entityManager->persist($lead);

            $this->entityManager->flush();

            // --- Send Activation Email ---
            try {
                $resetToken = $this->resetPasswordHelper->generateResetToken($employee);

                // IMPORTANT: Use UrlGeneratorInterface::ABSOLUTE_URL for email links
                $activationUrl = $this->generateUrl(
                    'app_reset_password', // Assuming this is the route name from ResetPasswordBundle
                    ['token' => $resetToken->getToken()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                $emailMessage = (new Email())
                    ->from($this->getParameter('app.mail_from')) // Configure sender in services.yaml or .env
                    ->to($employee->getEmail())
                    ->subject('Aktywuj swoje konto w Zaufany Detailer')
                    ->html($this->renderView(
                        'emails/employee_activation.html.twig', // Template created later
                        [
                            'employeeName' => $employee->getFirstName(),
                            'activationUrl' => $activationUrl,
                            'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(), // Pass lifetime for info
                        ]
                    ))
                ;

                $this->mailer->send($emailMessage);

                $this->addFlash('success', 'Warsztat i konto pracownika zostały utworzone. E-mail aktywacyjny wysłany.');
            } catch (ResetPasswordExceptionInterface $e) {
                $this->logger->error(
                    sprintf('Failed to generate reset token for lead ID %s: %s', $lead->getId(), $e->getReason()),
                    ['exception' => $e]
                );
                $this->addFlash('error', sprintf(
                    'Nie udało się wysłać e-maila aktywacyjnego: %s. Skontaktuj się z pracownikiem manualnie.',
                    $e->getReason()
                ));
            } catch (\Throwable $e) {
                // Catch potential mailer errors
                $this->logger->error(
                    sprintf('Error sending activation email for lead ID %s: %s', $lead->getId(), $e->getMessage()),
                    ['exception' => $e]
                );
                $this->addFlash('error', 'Wystąpił błąd podczas wysyłania e-maila aktywacyjnego. Skontaktuj się z pracownikiem manualnie.');
            }
            // --- End Send Activation Email ---

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin_panel/process_lead.html.twig', [
            'lead' => $lead,
            'form' => $form->createView(),
        ]);
    }
}
