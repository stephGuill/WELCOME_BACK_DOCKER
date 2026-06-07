<?php

namespace App\Controller;

use App\Entity\HomepageContent;
use App\Repository\HomepageContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        HomepageContentRepository $repository,
        EntityManagerInterface $entityManager
    ): Response {
        $content = $repository->findOneBy([]);

        if (!$content) {
            $content = (new HomepageContent())
                ->setTitle('Bienvenue sur UNIT_SYMFONY')
                ->setMessage('Base Symfony prête avec Docker, MySQL et persistance des données.')
                ->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->persist($content);
            $entityManager->flush();
        }

        return $this->render('home/index.html.twig', [
            'content' => $content,
        ]);
    }
}
