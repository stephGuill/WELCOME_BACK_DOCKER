<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// Commande Symfony CLI : php bin/console app:bootstrap-users
// Crée ou met à jour les comptes admin et user par défaut au premier démarrage
#[AsCommand(name: 'app:bootstrap-users', description: 'Cree les comptes admin et user par defaut.')]
class BootstrapUsersCommand extends Command
{
    // Injection des services nécessaires via le constructeur (autowiring Symfony)
    public function __construct(
        private EntityManagerInterface $entityManager,       // gestion des entités Doctrine
        private UserRepository $userRepository,              // accès aux utilisateurs en base
        private UserPasswordHasherInterface $passwordHasher, // hachage sécurisé des mots de passe
    ) {
        parent::__construct();
    }

    // Méthode principale exécutée par la commande
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Crée ou met à jour les deux comptes de démonstration
        $this->createOrUpdateUser('admin@unit.local', 'Admin1234!', ['ROLE_ADMIN'], $output);
        $this->createOrUpdateUser('user@unit.local', 'User1234!', ['ROLE_USER'], $output);

        $this->entityManager->flush(); // exécute tous les INSERT/UPDATE en une seule transaction
        $output->writeln('Comptes initialises.');

        return Command::SUCCESS; // code de sortie 0 = succès
    }

    // Crée un nouvel utilisateur ou met à jour un utilisateur existant (upsert)
    private function createOrUpdateUser(string $email, string $plainPassword, array $roles, OutputInterface $output): void
    {
        // findOneBy retourne l'utilisateur existant ou null (on crée alors un nouveau User)
        $user = $this->userRepository->findOneBy(['email' => $email]) ?? new User();
        $user->setEmail($email);
        $user->setRoles($roles);
        // hashPassword() applique l'algorithme configuré dans security.yaml (bcrypt/argon2)
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));

        $this->entityManager->persist($user); // prépare l'INSERT ou UPDATE
        $output->writeln(sprintf('Compte pret : %s', $email));
    }
}