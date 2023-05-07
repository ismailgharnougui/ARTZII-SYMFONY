<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 *
 * @ORM\Table(name="article", uniqueConstraints={@ORM\UniqueConstraint(name="ArtLib", columns={"ArtLib"})}, indexes={@ORM\Index(name="fk_id_user", columns={"id_user"})})
 * @ORM\Entity
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
     * @Assert\NotBlank(message="Le champ ne doit pas être vide.")
     * @Assert\Length(max=20, maxMessage="Le champ ne doit pas dépasser {{ limit }} caractères.")
     * @Assert\Regex(pattern="/^[^0-9]*$/", message="Le champ ne doit pas contenir de chiffres.")
     */
    private $artlib;

    /**
     * @var string
     *
     * @ORM\Column(name="ArtDesc", type="string", length=200, nullable=false)
     * @Assert\NotBlank(message="Le champ ne doit pas être vide.")
     * @Assert\Length(max=200, maxMessage="Le champ ne doit pas dépasser {{ limit }} caractères.")
     */
    private $artdesc;

    /**
     * @var int
     *
     * @ORM\Column(name="ArtDispo", type="integer", nullable=false)
     * * @Assert\NotBlank(message="Le champ ne doit pas être vide.")
     */
    private $artdispo;

    /**
     * @var string
     *
     * @ORM\Column(name="ArtImg", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Le champ ne doit pas être vide.")
     * @Assert\Length(max=255, maxMessage="Le champ ne doit pas dépasser {{ limit }} caractères.")
     */
    private $artimg;


    /**
     * @var string
     *
     * @ORM\Column(name="QrCode", type="string", length=255, nullable=true)
     */
    private $qrcode;

    /**
     * @var float
     *
     * @ORM\Column(name="ArtPrix", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotBlank(message="Le champ ne doit pas être vide.")

     * @Assert\Positive(message="La valeur doit être positive.")
     */
    private $artprix;



    /**
     * @var string
     *

     * @ORM\Column(name="CatLib", type="string", length=20, nullable=false)
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="catlib", referencedColumnName="catlib")
     * })
     */


    private $catlib;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $id_user;



    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $note;

    /**
     * @return int
     */
    public function getArtid(): int
    {
        return $this->artid;
    }

    /**
     * @param int $artid
     */
    public function setArtid(int $artid): void
    {
        $this->artid = $artid;
    }

    /**
     * @return string
     */
    public function getArtlib(): string
    {
        return $this->artlib;
    }

    /**
     * @param string $artlib
     */
    public function setArtlib(string $artlib): void
    {
        $this->artlib = $artlib;
    }

    /**
     * @return string
     */
    public function getArtdesc(): string
    {
        return $this->artdesc;
    }

    /**
     * @param string $artdesc
     */
    public function setArtdesc(string $artdesc): void
    {
        $this->artdesc = $artdesc;
    }

    /**
     * @return int
     */
    public function getArtdispo(): int
    {
        return $this->artdispo;
    }

    /**
     * @param int $artdispo
     */
    public function setArtdispo(int $artdispo): void
    {
        $this->artdispo = $artdispo;
    }

    /**
     * @return string
     */
    public function getArtimg(): string
    {
        return $this->artimg;
    }

    /**
     * @param string $artimg
     */
    public function setArtimg(string $artimg): void
    {
        $this->artimg = $artimg;
    }

    /**
     * @return float
     */
    public function getArtprix(): float
    {
        return $this->artprix;
    }

    /**
     * @param float $artprix
     */
    public function setArtprix(float $artprix): void
    {
        $this->artprix = $artprix;
    }



    /**
     * @return string
     */
    public function getCatlib(): string
    {
        return $this->catlib;
    }

    /**
     * @param string $catlib
     */
    public function setCatlib(Categorie $catlib): void
    {
        $this->catlib = $catlib;
    }

    public function getId(): Utilisateur
    {
        return $this->id_user;
    }


    public function setId(Utilisateur $id_user): void
    {
        $this->id_user = $id_user;
    }

    /**
     * @return string
     */
    public function getQrcode(): string
    {
        return $this->qrcode;
    }

    /**
     * @param string $qrcode
     */
    public function setQrcode(string $qrcode): void
    {
        $this->qrcode = $qrcode;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note): void
    {
        $this->note = $note;
    }



}
