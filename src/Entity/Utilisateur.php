<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur", uniqueConstraints={@ORM\UniqueConstraint(name="id_user", columns={"id_user"}), @ORM\UniqueConstraint(name="mail", columns={"mail"}), @ORM\UniqueConstraint(name="cin", columns={"cin"})})
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 */
class Utilisateur
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id_user", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idUser;
   /**
     * @Assert\NotBlank(message="Le mot de passe est requis")
     * @Assert\Length(max=50, maxMessage="Le mot de passe doit avoir au maximum {{ limit }} caractères")
     *
     * @ORM\Column(name="password", type="string", length=50, nullable=false)
     */
    private $password;

    /**
     * @Assert\NotBlank(message="L'adresse mail est requise")
     * @Assert\Email(message="L'adresse mail n'est pas valide")
     * @Assert\Length(max=50, maxMessage="L'adresse mail doit avoir au maximum {{ limit }} caractères")
     *
     * @ORM\Column(name="mail", type="string", length=50, nullable=false)
     */
    private $mail;

    /**
     * @Assert\NotBlank(message="Le nom est requis")
     * @Assert\Length(max=50, maxMessage="Le nom doit avoir au maximum {{ limit }} caractères")
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @Assert\NotBlank(message="Le prénom est requis")
     * @Assert\Length(max=50, maxMessage="Le prénom doit avoir au maximum {{ limit }} caractères")
     *
     * @ORM\Column(name="prenom", type="string", length=50, nullable=false)
     */
    private $prenom;

    /**
     * @Assert\NotBlank(message="L'adresse est requise")
     * @Assert\Length(max=100, maxMessage="L'adresse doit avoir au maximum {{ limit }} caractères")
     *
     * @ORM\Column(name="adresse", type="string", length=100, nullable=false)
     */
    private $adresse;

    /**
     * @Assert\NotBlank(message="Le rôle est requis")
     * @Assert\Length(max=30, maxMessage="Le rôle doit avoir au maximum {{ limit }} caractères")
     *
     * @ORM\Column(name="role", type="string", length=30, nullable=false)
     */
    private $role;

    /**
     * @Assert\NotBlank(message="Le numéro de CIN est requis")
     * @Assert\Regex(pattern="/^[0-9]{8}$/", message="Le numéro de CIN doit être composé de 8 chiffres")
     *
     * @ORM\Column(name="cin", type="string", length=8, nullable=false)
     */
    private $cin;

    /**
     * @Assert\NotBlank(message="Le numéro de téléphone est requis")
     * @Assert\Regex(pattern="/^[0-9]{8}$/", message="Le numéro de téléphone doit être composé de 8 chiffres")
     *
     * @ORM\Column(name="numero", type="string", length=50, nullable=false)
     */
    private $numero;

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

}

