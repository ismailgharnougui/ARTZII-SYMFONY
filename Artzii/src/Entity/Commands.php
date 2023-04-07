<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commands
 *
 * @ORM\Table(name="commands", indexes={@ORM\Index(name="id_client", columns={"id_client"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CommandsRepository")
 */
class Commands
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_commande", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateCommande = 'CURRENT_TIMESTAMP';

    /**
     * @var string|null
     *
     * @ORM\Column(name="mode_livraison", type="string", length=30, nullable=true)
     */
    private $modeLivraison;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mode_paiement", type="string", length=30, nullable=true)
     */
    private $modePaiement;

    /**
     * @var float|null
     *
     * @ORM\Column(name="cout_totale", type="float", precision=10, scale=0, nullable=true)
     */
    private $coutTotale;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etat_commande", type="string", length=30, nullable=true, options={"default"="En attente"})
     */
    private $etatCommande = 'En attente';

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=30, nullable=false)
     */
    private $adresse;

    /**
     * @var \App\Entity\Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_client", referencedColumnName="idU")
     * })
     */
    private $idClient;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getModeLivraison(): ?string
    {
        return $this->modeLivraison;
    }

    public function setModeLivraison(?string $modeLivraison): self
    {
        $this->modeLivraison = $modeLivraison;

        return $this;
    }

    public function getModePaiement(): ?string
    {
        return $this->modePaiement;
    }

    public function setModePaiement(?string $modePaiement): self
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    public function getCoutTotale(): ?float
    {
        return $this->coutTotale;
    }

    public function setCoutTotale(?float $coutTotale): self
    {
        $this->coutTotale = $coutTotale;

        return $this;
    }

    public function getEtatCommande(): ?string
    {
        return $this->etatCommande;
    }

    public function setEtatCommande(?string $etatCommande): self
    {
        $this->etatCommande = $etatCommande;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getIdClient(): ?Utilisateur
    {
        return $this->idClient;
    }

    public function setIdClient(?Utilisateur $idClient): self
    {
        $this->idClient = $idClient;

        return $this;
    }


}
