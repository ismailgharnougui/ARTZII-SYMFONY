<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity
 */
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="CatId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $catid;

    /**
     * @var string
     *
     * @ORM\Column(name="CatLib", type="string", length=20, nullable=false)
     * @Assert\NotBlank(message="Le champ ne doit pas être vide.")
     * @Assert\Length(max=20, maxMessage="Le champ ne doit pas dépasser {{ limit }} caractères.")
     * @Assert\Regex(pattern="/^[^0-9]*$/", message="Le champ ne doit pas contenir de chiffres.")
     */
    private $catlib;

    /**
     * @return int
     */
    public function getCatid(): int
    {
        return $this->catid;
    }

    /**
     * @param int $catid
     */
    public function setCatid(int $catid): void
    {
        $this->catid = $catid;
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
    public function setCatlib(string $catlib): void
    {
        $this->catlib = $catlib;
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
        return $this->catlib;
    }
}