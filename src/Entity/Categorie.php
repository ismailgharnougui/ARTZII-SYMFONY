<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $CatLib = null;




    // JOIN:
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'catLib')]
    private $products;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatLib(): ?string
    {
        return $this->CatLib;
    }

    public function setCatLib(string $CatLib): self
    {
        $this->CatLib = $CatLib;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param mixed $products
     */
    public function setProducts($products): void
    {
        $this->products = $products;
    }


}
