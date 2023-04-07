<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="article", indexes={@ORM\Index(name="fk_artiste", columns={"IdArtiste"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="ArtId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $artid;

    /**
     * @var string
     *
     * @ORM\Column(name="ArtLib", type="string", length=20, nullable=false)
     */
    private $artlib;

    /**
     * @var string
     *
     * @ORM\Column(name="ArtDesc", type="string", length=20, nullable=false)
     */
    private $artdesc;

    /**
     * @var int
     *
     * @ORM\Column(name="ArtDispo", type="integer", nullable=false)
     */
    private $artdispo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ArtImg", type="string", length=4000, nullable=true)
     */
    private $artimg;

    /**
     * @var int
     *
     * @ORM\Column(name="ArtPrix", type="integer", nullable=false)
     */
    private $artprix;

    /**
     * @var string
     *
     * @ORM\Column(name="CatLib", type="string", length=20, nullable=false)
     */
    private $catlib;

    /**
     * @var \app\Entity\Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdArtiste", referencedColumnName="idU")
     * })
     */
    private $idartiste;

    public function getArtid(): ?int
    {
        return $this->artid;
    }

    public function getArtlib(): ?string
    {
        return $this->artlib;
    }

    public function setArtlib(string $artlib): self
    {
        $this->artlib = $artlib;

        return $this;
    }

    public function getArtdesc(): ?string
    {
        return $this->artdesc;
    }

    public function setArtdesc(string $artdesc): self
    {
        $this->artdesc = $artdesc;

        return $this;
    }

    public function getArtdispo(): ?int
    {
        return $this->artdispo;
    }

    public function setArtdispo(int $artdispo): self
    {
        $this->artdispo = $artdispo;

        return $this;
    }

    public function getArtimg(): ?string
    {
        return $this->artimg;
    }

    public function setArtimg(?string $artimg): self
    {
        $this->artimg = $artimg;

        return $this;
    }

    public function getArtprix(): ?int
    {
        return $this->artprix;
    }

    public function setArtprix(int $artprix): self
    {
        $this->artprix = $artprix;

        return $this;
    }

    public function getCatlib(): ?string
    {
        return $this->catlib;
    }

    public function setCatlib(string $catlib): self
    {
        $this->catlib = $catlib;

        return $this;
    }

    public function getIdartiste(): ?Utilisateur
    {
        return $this->idartiste;
    }

    public function setIdartiste(?Utilisateur $idartiste): self
    {
        $this->idartiste = $idartiste;

        return $this;
    }

}
