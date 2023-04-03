<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idU = null;


    #[Assert\NotBlank(message: "nom User doit etre non vide")]
    #[ORM\Column(length: 255)]
    private ?string $nomU = null;
    #[Assert\NotBlank(message: "prenom User doit etre non vide")]
    #[ORM\Column(length: 255)]
    private ?string $prenomU = null;
    #[Assert\NotBlank(message: "email User doit etre non vide")]
    #[ORM\Column(length: 255)]
    private ?string $emailU = null;
    #[Assert\NotBlank(message: "mdp User doit etre non vide")]
    #[ORM\Column(length: 255)]
    private ?string $mdpU = null;
    #[Assert\NotBlank(message: "role User doit etre non vide")]
    #[ORM\Column(length: 255)]
    private ?string $roleU = null;
    #[Assert\NotBlank(message: "adresse User doit etre non vide")]
    #[ORM\Column(length: 255)]
    private ?string $adresse = null;



    public function getidU(): ?int
    {
        return $this->idU;
    }

    public function getnomU(): ?string
    {
        return $this->nomU;
    }
    public function getprenomU(): ?string
    {
        return $this->prenomU;
    }
    public function getemailU(): ?string
    {
        return $this->emailU;
    }
    public function getmdpU(): ?string
    {
        return $this->mdpU;
    }
    public function getroleU(): ?string
    {
        return $this->roleU;
    }
    public function getadresse(): ?string
    {
        return $this->adresse;
    }

    public function setidU(int $idU): self
    {
        $this->idU = $idU;

        return $this;
    }



    public function setnomU(string $nomU): self
    {
        $this->nomU = $nomU;

        return $this;
    }



    public function setprenomU(string $prenomU): self
    {
        $this->prenomU = $prenomU;

        return $this;
    }



    public function setemailU(string $emailU): self
    {
        $this->emailU = $emailU;

        return $this;
    }



    public function setmdpU(string $mdpU): self
    {
        $this->mdpU = $mdpU;

        return $this;
    }


    public function setroleU(string $roleU): self
    {
        $this->roleU = $roleU;

        return $this;
    }


    public function setadresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }





}
