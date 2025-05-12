<?php

declare(strict_types=1);

namespace App\AdminPanel\Controller;

use App\AdminPanel\Form\ServiceType;
use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Uid\Uuid;

#[Route('/admin/service', name: 'admin_service_')]
class ServiceController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ServiceRepository $serviceRepository,
        private readonly SluggerInterface $slugger,
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin_panel/service/index.html.twig', [
            'services' => $this->serviceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->setId(Uuid::v7());
            $service->setSlug($this->slugger->slug($service->getName(), locale: 'pl')->lower()->toString());

            $this->entityManager->persist($service);
            $this->entityManager->flush();

            $this->addFlash('success', 'Usługa została utworzona.');

            return $this->redirectToRoute('admin_service_index');
        }

        return $this->render('admin_panel/service/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->setSlug($this->slugger->slug($service->getName(), locale: 'pl')->lower()->toString());
            $this->entityManager->flush();

            $this->addFlash('success', 'Usługa została zaktualizowana.');

            return $this->redirectToRoute('admin_service_index');
        }

        return $this->render('admin_panel/service/edit.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Service $service): Response
    {
        $this->entityManager->remove($service);
        $this->entityManager->flush();

        $this->addFlash('success', 'Usługa została usunięta.');

        return $this->redirectToRoute('admin_service_index');
    }
}
