<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandArticles
 *
 * @ORM\Table(name="command_articles", indexes={@ORM\Index(name="fk_art", columns={"article_id"}), @ORM\Index(name="fk_com", columns={"command_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CommandArticlesRepository")
 */
class CommandArticles
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
     * @var \App\Entity\Article
     *
     * @ORM\ManyToOne(targetEntity="Article")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="article_id", referencedColumnName="ArtId")
     * })
     */
    private $article;

    /**
     * @var \App\Entity\Commands
     *
     * @ORM\ManyToOne(targetEntity="Commands")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="command_id", referencedColumnName="id")
     * })
     */
    private $command;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;
        return $this;
    }

    public function getCommand(): ?Commands
    {
        return $this->command;
    }

    public function setCommand(?Commands $command): self
    {
        $this->command = $command;

        return $this;
    }
}
