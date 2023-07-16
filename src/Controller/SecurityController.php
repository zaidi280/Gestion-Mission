<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('ROLE_EMPLOYE')) {
            return $this->redirectToRoute('app_userindex');
        } elseif ($this->isGranted('ROLE_CHEF_DE_PARC')) {
            return $this->redirectToRoute('app_voiture');
        } elseif ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin');
        } elseif ($this->isGranted('ROLE_SCGEN')) {
            return $this->redirectToRoute('valid_miss');
        } elseif ($this->isGranted('ROLE_PERSONNEL')) {
            return $this->redirectToRoute('ord-miss');
        } elseif ($this->isGranted('ROLE_FINANCIER')) {
            return $this->redirectToRoute('indsf');
        }


        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
