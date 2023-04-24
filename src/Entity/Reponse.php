<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{





    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"idRep ")]
    private ?int $idRep = null;

    #[ORM\Column(name:"dateRep",type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateRep = null;

    #[ORM\Column(length: 255,name:"contenuRep")]
    private ?string $contenuRep = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'] )]
    #[ORM\JoinColumn(name:"idreclamation ", referencedColumnName:"id",nullable: false)]
    private ?Reclamation $idreclamation = null;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('contenuRep', new Assert\NotBlank());
    }
        public function getIdRep(): ?int
    {
        return $this->idRep;
    }

    public function setIdRep(int $idRep): self
    {
        $this->idRep = $idRep;

        return $this;
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

    public function getContenuRep(): ?string
    {
        return $this->contenuRep;
    }

    public function setContenuRep(string $contenuRep): self
    {
        $this->contenuRep = $contenuRep;

        return $this;
    }

    public function getIdreclamation(): ?Reclamation
    {
        return $this->idreclamation;
    }

    public function setIdreclamation(Reclamation $idreclamation): self
    {
        $this->idreclamation = $idreclamation;

        return $this;
    }
}
