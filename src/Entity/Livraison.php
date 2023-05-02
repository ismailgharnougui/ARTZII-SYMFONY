<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $etatLiv = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $dateLiv = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?float $prixLiv = null;

    #[ORM\ManyToOne(inversedBy: 'livraisons')]
    #[Assert\NotBlank]
    private ?Livreur $livreur = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Assert\NotBlank]
    private ?Commande $commande = null;

    public function __construct()
    {
        $this->dateLiv = new \DateTime('now');
        $this->etatLiv = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isEtatLiv(): ?bool
    {
        return $this->etatLiv;
    }

    public function setEtatLiv(bool $etatLiv): self
    {
        $this->etatLiv = $etatLiv;

        return $this;
    }

    public function getDateLiv(): ?\DateTimeInterface
    {
        return $this->dateLiv;
    }

    public function setDateLiv(\DateTimeInterface $dateLiv): self
    {
        $this->dateLiv = $dateLiv;

        return $this;
    }

    public function getPrixLiv(): ?float
    {
        return $this->prixLiv;
    }

    public function setPrixLiv(float $prixLiv): self
    {
        $this->prixLiv = $prixLiv;

        return $this;
    }

    public function getLivreur(): ?Livreur
    {
        return $this->livreur;
    }

    public function setLivreur(?Livreur $livreur): self
    {
        $this->livreur = $livreur;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }
}
