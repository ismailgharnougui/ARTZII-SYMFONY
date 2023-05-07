<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 *@ORM\Table(name="reclamation")
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column
     */
    private $id;

    /**
     * @ORM\Column(length=255, name="TypeR")
     */
private  $TypeR;
    /**
     * @ORM\Column(type=Types::DATETIME_MUTABLE, name="dateR")
     */

    private $dateR;
    /**
     * @ORM\Column(length=255)
     */

private $etat;


    /**
     * @ORM\Column(length=255)
     */

private $Description;


      /**
     * @ORM\Column(length=255)
     */
    private $objet;



    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $id_user;




    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('TypeR', new Assert\NotBlank());
        $metadata->addPropertyConstraint('Description', new Assert\NotBlank());
        $metadata->addPropertyConstraint('objet', new Assert\NotBlank());

        // Uncomment to set length constraints on TypeR, Description, and objet
        /*
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
        ]));
        */
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

    public function getIdUser(): ?Utilisateur
    {
        return $this->id_user;
    }

    public function setIdUser(?Utilisateur $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }




}

