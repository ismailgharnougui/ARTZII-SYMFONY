<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Livreurs
 *
 * @ORM\Table(name="livreurs", uniqueConstraints={@ORM\UniqueConstraint(name="num_tel", columns={"num_tel"})})
 * @ORM\Entity
 */
class Livreurs
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
     * @var string
     *
     * @ORM\Column(name="num_tel", type="string", length=50, nullable=false)
     */
    private $numTel;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="region_livreur", type="string", length=255, nullable=false)
     */
    private $regionLivreur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    public function setNumTel(string $numTel): self
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getRegionLivreur(): ?string
    {
        return $this->regionLivreur;
    }

    public function setRegionLivreur(string $regionLivreur): self
    {
        $this->regionLivreur = $regionLivreur;

        return $this;
    }


}
