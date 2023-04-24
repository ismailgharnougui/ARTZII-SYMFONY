<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255,name:"TypeR")]
    private ?string $TypeR = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE,name:"dateR")]
    private ?\DateTimeInterface $dateR = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    private ?string $objet = null;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    #[ORM\JoinColumn(name:"iduser", referencedColumnName:"id",nullable: false)]
    private ?User $User = null;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('TypeR', new Assert\NotBlank());
        $metadata->addPropertyConstraint('Description', new Assert\NotBlank());

        $metadata->addPropertyConstraint('objet', new Assert\NotBlank());

        /**
        $metadata->addPropertyConstraint('TypeR', new Assert\Length([
            'min' => 3,
            'max' => 20,
            'minMessage' => 'TypeR must be at least {{ limit }} characters long',
            'maxMessage' => 'TypeR first name cannot be longer than {{ limit }} characters',
        ]));
        $metadata->addPropertyConstraint('Description', new Assert\Length([
            'min' => 6,
            'max' => 90,
            'minMessage' => 'Description must be at least {{ limit }} characters long',
            'maxMessage' => 'Description first name cannot be longer than {{ limit }} characters',
        ]));
        $metadata->addPropertyConstraint('objet', new Assert\Length([
            'min' => 6,
            'max' => 30,
            'minMessage' => 'Objet must be at least {{ limit }} characters long',
            'maxMessage' => 'Objet first name cannot be longer than {{ limit }} characters',
        ]));*/
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeR(): ?string
    {
        return $this->TypeR;
    }

    public function setTypeR(string $TypeR): self
    {
        $this->TypeR = $TypeR;

        return $this;
    }

    public function getDateR(): ?\DateTimeInterface
    {
        return $this->dateR;
    }

    public function setDateR(\DateTimeInterface $dateR): self
    {
        $this->dateR = $dateR;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }





}
