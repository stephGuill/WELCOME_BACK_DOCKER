<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

// Contrôleur gérant les formulaires de connexion (admin et utilisateur)
class SecurityController extends AbstractController
{
    // Route GET/POST /login/admin : affiche le formulaire de connexion administrateur
    // AuthenticationUtils injecte les infos de la dernière tentative (email saisi, erreur éventuelle)
    #[Route('/login/admin', name: 'app_login_admin', methods: ['GET', 'POST'])]
    public function adminLogin(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('auth/admin_login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(), // pré-remplit le champ email
            'error' => $authenticationUtils->getLastAuthenticationError(), // affiche l'erreur si login échoué
        ]);
    }

    // Route GET/POST /login/user : affiche le formulaire de connexion utilisateur standard
    #[Route('/login/user', name: 'app_login_user', methods: ['GET', 'POST'])]
    public function userLogin(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('auth/user_login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    // Route POST /logout : la déconnexion est entièrement gérée par le firewall Symfony
    // Cette méthode ne sera jamais exécutée (le firewall intercepte avant)
    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(): void
    {
        throw new \LogicException('Cette methode est interceptee par le firewall logout.');
    }
}