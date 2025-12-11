<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->redirectToRoute('duiklog_overzicht');
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'error'         => $error,
            'last_username' => $authenticationUtils->getLastUsername() ?? 'tcr.crawford@gmail.com',
        ]);
    }
}
