<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Livraison
 *
 * @ORM\Table(name="livraison")
 * @ORM\Entity
 */
class Livraison
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_livraison", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLivraison;

    /**
     * @var string
     *
     * @ORM\Column(name="etatLiv", type="string", length=255, nullable=false)
     */
    private $etatliv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateLiv", type="date", nullable=false)
     */
    private $dateliv;

    /**
     * @var float
     *
     * @ORM\Column(name="prixLiv", type="float", precision=10, scale=0, nullable=false)
     */
    #[Assert\PositiveOrZero(message: 'Prix ne doit pas etre negative!')]
    private $prixliv;

    public function getIdLivraison(): ?int
    {
        return $this->idLivraison;
    }

    public function getEtatliv(): ?string
    {
        return $this->etatliv;
    }

    public function setEtatliv(string $etatliv): self
    {
        $this->etatliv = $etatliv;

        return $this;
    }

    public function getDateliv(): ?\DateTimeInterface
    {
        return $this->dateliv;
    }

    public function setDateliv(\DateTimeInterface $dateliv): self
    {
        $this->dateliv = $dateliv;

        return $this;
    }

    public function getPrixliv(): ?float
    {
        return $this->prixliv;
    }

    public function setPrixliv(float $prixliv): self
    {
        $this->prixliv = $prixliv;

        return $this;
    }


}
