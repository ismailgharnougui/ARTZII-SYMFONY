<?php
namespace App\Entity;

use App\Repository\EchangeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EchangeRepository::class)]
class Echange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: 'Product')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $produit_echange = null;

    #[ORM\ManyToOne(targetEntity: 'Product')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $produit_offert = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu_echange = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu_offre = null;

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\Choice(choices: ['en attente', 'en cours', 'terminé'])]
    private ?string $statut = null;

    #[ORM\ManyToOne(targetEntity: 'User')]
    private ?User $livreur = null;

    #[ORM\OneToMany(mappedBy: 'echange', targetEntity: Notification::class, cascade: ['remove'])]
    private $notifications;


    public function __construct()
    {
        $this->notifications = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduitEchange(): ?Product
    {
        return $this->produit_echange;
    }

    public function setProduitEchange(Product $produit_echange): self
    {
        $this->produit_echange = $produit_echange;

        return $this;
    }

    public function getProduitOffert(): ?Product
    {
        return $this->produit_offert;
    }

    public function setProduitOffert(Product $produit_offert): self
    {
        $this->produit_offert = $produit_offert;

        return $this;
    }

    public function getLieuEchange(): ?string
    {
        return $this->lieu_echange;
    }

    public function setLieuEchange(string $lieu_echange): self
    {
        $this->lieu_echange = $lieu_echange;

        return $this;
    }

    public function getLieuOffre(): ?string
    {
        return $this->lieu_offre;
    }

    public function setLieuOffre(string $lieu_offre): self
    {
        $this->lieu_offre = $lieu_offre;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getLivreur(): ?User
    {
        return $this->livreur;
    }

    public function setLivreur(?User $livreur): self
    {
        $this->livreur = $livreur;

        return $this;
    }
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setEchange($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getEchange() === $this) {
                $notification->setEchange(null);
            }
        }

        return $this;
    }
}
