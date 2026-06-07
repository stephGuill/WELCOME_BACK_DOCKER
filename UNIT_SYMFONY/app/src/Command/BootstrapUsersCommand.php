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

#[AsCommand(name: 'app:bootstrap-users', description: 'Cree les comptes admin et user par defaut.')]
class BootstrapUsersCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->createOrUpdateUser('admin@unit.local', 'Admin1234!', ['ROLE_ADMIN'], $output);
        $this->createOrUpdateUser('user@unit.local', 'User1234!', ['ROLE_USER'], $output);

        $this->entityManager->flush();
        $output->writeln('Comptes initialises.');

        return Command::SUCCESS;
    }

    private function createOrUpdateUser(string $email, string $plainPassword, array $roles, OutputInterface $output): void
    {
        $user = $this->userRepository->findOneBy(['email' => $email]) ?? new User();
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));

        $this->entityManager->persist($user);
        $output->writeln(sprintf('Compte pret : %s', $email));
    }
}