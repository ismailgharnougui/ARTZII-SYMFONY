<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Instructeur
 *
 * @ORM\Table(name="instructeur")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\InstructeurRepository")
 */
class Instructeur
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="cantact_info", type="text", length=65535, nullable=false)
     */
    private $cantactInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="qualifications", type="text", length=65535, nullable=false)
     */
    private $qualifications;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCantactInfo(): ?string
    {
        return $this->cantactInfo;
    }

    public function setCantactInfo(string $cantactInfo): self
    {
        $this->cantactInfo = $cantactInfo;

        return $this;
    }

    public function getQualifications(): ?string
    {
        return $this->qualifications;
    }

    public function setQualifications(string $qualifications): self
    {
        $this->qualifications = $qualifications;

        return $this;
    }


}
