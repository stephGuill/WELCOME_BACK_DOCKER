<?php

namespace App\Controller;

use App\Entity\HomepageContent;
use App\Repository\HomepageContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Contrôleur de la page d'accueil publique
class HomeController extends AbstractController
{
    // Route / : page d'accueil (accessible à tous, sans authentification)
    #[Route('/', name: 'app_home')]
    public function index(
        HomepageContentRepository $repository, // injecté par Symfony DI
        EntityManagerInterface $entityManager   // injecté par Symfony DI
    ): Response {
        // Cherche le premier enregistrement HomepageContent en base
        $content = $repository->findOneBy([]);

        // Si aucun contenu n'existe encore (premier lancement), en crée un par défaut
        if (!$content) {
            $content = (new HomepageContent())
                ->setTitle('Bienvenue sur UNIT_SYMFONY')
                ->setMessage('Base Symfony prête avec Docker, MySQL et persistance des données.')
                ->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->persist($content); // ajoute l'entité dans le gestionnaire
            $entityManager->flush();           // exécute le INSERT en base
        }

        // Transmet le contenu au template Twig pour l'affichage
        return $this->render('home/index.html.twig', [
            'content' => $content,
        ]);
    }
}
