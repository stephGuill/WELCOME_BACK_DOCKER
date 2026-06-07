<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

// Entité Doctrine mappée vers la table 'app_user'
// Implémente UserInterface (requis par Symfony Security) et PasswordAuthenticatedUserInterface (mots de passe hachés)
// UniqueEntity garantit qu'un email ne peut être enregistré qu'une seule fois
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'app_user')]
#[UniqueEntity(fields: ['email'], message: 'Cette adresse e-mail est deja utilisee.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Clé primaire auto-incrémentée
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Email unique, max 180 caractères (identifiant de connexion)
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    // Rôles stockés en JSON dans la base (ex: ["ROLE_ADMIN"])
    #[ORM\Column]
    private array $roles = [];

    // Mot de passe haché (bcrypt/argon2) — jamais stocké en clair
    #[ORM\Column]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    // Normalise l'email en minuscules pour éviter les doublons dus à la casse
    public function setEmail(string $email): static
    {
        $this->email = strtolower($email);

        return $this;
    }

    // getUserIdentifier() retourne l'identifiant unique de l'utilisateur (l'email ici)
    // Utilisé par Symfony Security pour charger l'utilisateur depuis la session
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    // getRoles() retourne toujours au minimum ['ROLE_USER'] (exigence de Symfony Security)
    // array_unique() élimine les doublons si ROLE_USER est déjà dans $this->roles
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_values(array_unique($roles));
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    // Méthode de l'interface UserInterface : efface les données sensibles temporaires
    // (ex: mot de passe en clair stocké temporairement pendant l'authentification)
    public function eraseCredentials(): void
    {
    }
}