<?php

declare(strict_types=1);

namespace App\AdminPanel\Controller;

use App\AdminPanel\Entity\Admin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response; // Or Attribute if using PHP 8
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminLoginController extends AbstractController
{
    #[Route('/admin/login', name: 'admin_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Redirect if already logged in (user object exists and has the role)
        // Check if the user is logged in and has the required role
        if ($this->getUser() instanceof Admin) { // Check if the user object exists and is of the correct type
            // Optionally, add a specific role check if needed, although the firewall access_control handles this
            // if ($this->isGranted('ROLE_SUPER_ADMIN')) { ... }
            return $this->redirectToRoute('admin_dashboard');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin_panel/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/admin/logout', name: 'admin_logout')]
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
