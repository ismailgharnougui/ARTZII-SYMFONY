<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $ArtId = null;


    #[Assert\NotBlank(message: "nom produit doit etre non vide")]
    #[Assert\Length(
        min: 5,
        minMessage: "Entrer un nom au min de 5 caracteres"
    )]
    #[ORM\Column(length: 255)]
    private ?string $ArtLib = null;

    #[Assert\NotBlank(message: "description doit etre non vide")]
    #[Assert\Length(
        min: 10,
        max: 150,
        minMessage: "doit etre > 10",
        maxMessage: "doit etre < à 150"
    )]
    #[ORM\Column(length: 500)]
    private ?string $ArtDesc = null;

    #[ORM\Column(nullable: true)]
    private ?int $ArtDispo = null;

    #[ORM\Column(length: 255)]
    private ?string $ArtImg = null;

    #[Assert\NotBlank(message: "prix doit etre non vide")]
    #[Assert\Range(
        min: 1,
        max: 9999999999,
        notInRangeMessage: "le prix doit etre valide"
    )]
    #[ORM\Column]
    private ?float $ArtPrix = null;



    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'products')]
    private $catLib;


    public function getId(): ?int
    {
        return $this->ArtId;
    }

    public function getArtLib(): ?string
    {
        return $this->ArtLib;
    }

    public function setArtLib(string $ArtLib): self
    {
        $this->ArtLib = $ArtLib;

        return $this;
    }

    public function getArtDesc(): ?string
    {
        return $this->ArtDesc;
    }

    public function setArtDesc(string $ArtDesc): self
    {
        $this->ArtDesc = $ArtDesc;

        return $this;
    }

    public function getArtDispo(): ?int
    {
        return $this->ArtDispo;
    }

    public function setArtDispo(int $ArtDispo): self
    {
        $this->ArtDispo = $ArtDispo;

        return $this;
    }

    public function getArtImg(): ?string
    {
        return $this->ArtImg;
    }

    public function setArtImg(string $ArtImg): self
    {
        $this->ArtImg = $ArtImg;

        return $this;
    }

    public function getArtPrix(): ?float
    {
        return $this->ArtPrix;
    }

    public function setArtPrix(float $ArtPrix): self
    {
        $this->ArtPrix = $ArtPrix;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCatLib()
    {
        return $this->catLib;
    }

    /**
     * @param mixed $catLib
     */
    public function setCatLib($catLib): void
    {
        $this->catLib = $catLib;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
