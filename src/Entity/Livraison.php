<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LivraisonRepository::class)
 */
class Livraison
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $etatLiv;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateLiv;

    /**
     * @ORM\Column(type="float")
     */
    private $prixLiv;

    /**
     * @ORM\ManyToOne(targetEntity=Livreur::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $livreur;

    /**
     * @ORM\OneToOne(targetEntity=Commands::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     */
    private $commande;

    public function __construct()
    {
        $this->dateLiv = new \DateTime('now');
        $this->etatLiv = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtatLiv(): ?string
    {
        return $this->etatLiv;
    }

    public function setEtatLiv(string $etatLiv): self
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

    public function getCommande(): ?Commands
    {
        return $this->commande;
    }

    public function setCommande(?Commands $commande): self
    {
        $this->commande = $commande;

        return $this;
    }
}
