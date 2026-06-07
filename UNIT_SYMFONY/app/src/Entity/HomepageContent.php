<?php

namespace App\Entity;

use App\Repository\HomepageContentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

// Entité Doctrine pour le contenu de la page d'accueil
// Stockée en base (table homepage_content) — permet de modifier le contenu sans redéployer
#[ORM\Entity(repositoryClass: HomepageContentRepository::class)]
class HomepageContent
{
    // Clé primaire auto-incrémentée
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Titre affiché sur la page d'accueil (varchar 255)
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    // Message de la page d'accueil (type TEXT : pas de limite de longueur)
    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    // Date/heure de dernière mise à jour (DATETIME_IMMUTABLE = immuable, pas de timezone)
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
