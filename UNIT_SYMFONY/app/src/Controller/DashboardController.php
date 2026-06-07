<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// Contrôleur gérant les espaces personnels après authentification
class DashboardController extends AbstractController
{
    // Route /admin : accessible uniquement aux utilisateurs ayant le rôle ROLE_ADMIN
    // Si un utilisateur sans ce rôle tente d'y accéder, Symfony renvoie un 403 Forbidden
    #[Route('/admin', name: 'app_admin_dashboard')]
    #[IsGranted('ROLE_ADMIN')]
    public function admin(): Response
    {
        // Rend le template Twig du tableau de bord administrateur
        return $this->render('dashboard/admin.html.twig');
    }

    // Route /user : accessible aux utilisateurs ayant au minimum ROLE_USER
    #[Route('/user', name: 'app_user_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function user(): Response
    {
        // Rend le template Twig de l'espace utilisateur
        return $this->render('dashboard/user.html.twig');
    }
}