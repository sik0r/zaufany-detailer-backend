<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkshopLandingController extends AbstractController
{
    #[Route('/dla-warsztatow', name: 'app_for_workshops')]
    public function index(): Response
    {
        return $this->render('frontend/dla_warsztatow/index.html.twig');
    }
}
