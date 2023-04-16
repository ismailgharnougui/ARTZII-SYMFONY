<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity
 */
class Utilisateur implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="idU", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idu;

    /**
     * @var string
     *
     * @ORM\Column(name="nomU", type="string", length=20, nullable=false)
     */
    private $nomu;

    /**
     * @var string
     *
     * @ORM\Column(name="prenomU", type="string", length=20, nullable=false)
     */
    private $prenomu;

    /**
     * @var string
     *
     * @ORM\Column(name="emailU", type="string", length=20, nullable=false)
     */
    private $emailu;

    /**
     * @var binary|null
     *
     * @ORM\Column(name="mdpU", type="binary", nullable=true)
     */
    private $mdpu;

    /**
     * @var string
     *
     * @ORM\Column(name="roleU", type="string", length=20, nullable=false)
     */
    private $roleu;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=20, nullable=false)
     */
    private $adresse;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idu = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdu(): ?int
    {
        return $this->idu;
    }

    public function getNomu(): ?string
    {
        return $this->nomu;
    }
    
    public function setNomu(string $nomu): self
    {
        $this->nomu = $nomu;

        return $this;
    }

    public function getPrenomu(): ?string
    {
        return $this->prenomu;
    }

    public function setPrenomu(string $prenomu): self
    {
        $this->prenomu = $prenomu;

        return $this;
    }

    public function getEmailu(): ?string
    {
        return $this->emailu;
    }

    public function setEmailu(string $emailu): self
    {
        $this->emailu = $emailu;

        return $this;
    }

    public function getMdpu()
    {
        return $this->mdpu;
    }

    public function setMdpu($mdpu): self
    {
        $this->mdpu = $mdpu;

        return $this;
    }

    public function getRoleu(): ?string
    {
        return $this->roleu;
    }

    public function setRoleu(string $roleu): self
    {
        $this->roleu = $roleu;

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

    public function getUserIdentifier(): string
{
    return $this->emailu;
}

public function getRoles(): array
{
    return [$this->roleu];
}

public function getPassword(): ?string
{
    return $this->mdpu;
}

public function getSalt(): ?string
{
    // leave this blank unless you're using bcrypt or Argon2i
    // these algorithms have salt built-in
    return null;
}

public function eraseCredentials()
{
    // if you store any temporary, sensitive data on the user, delete it here
}

public function getUsername(): string
{
    return $this->emailu;
}
}