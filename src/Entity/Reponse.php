<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Reponse
 *
 * @ORM\Table(name="reponse")
 * @ORM\Entity
 */
class Reponse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRep;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $contenuRep;

    /**
     * @ORM\ManyToOne(targetEntity=Reclamation::class, inversedBy="reponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reclamation;

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('contenuRep', new Assert\NotBlank());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRep(): ?\DateTimeInterface
    {
        return $this->dateRep;
    }

    public function setDateRep(\DateTimeInterface $dateRep): self
    {
        $this->dateRep = $dateRep;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContenuRep()
    {
        return $this->contenuRep;
    }

    /**
     * @param mixed $contenuRep
     */
    public function setContenuRep($contenuRep): void
    {
        $this->contenuRep = $contenuRep;
    }

    /**
     * @return mixed
     */
    public function getReclamation()
    {
        return $this->reclamation;
    }

    /**
     * @param mixed $reclamation
     */
    public function setReclamation($reclamation): void
    {
        $this->reclamation = $reclamation;
    }


}