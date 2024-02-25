<?php

namespace App\Entity;

use App\Repository\WishListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WishListRepository::class)]
class WishList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'wishList', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'wishLists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\OneToMany(mappedBy: 'wishList', targetEntity: Produit::class)]
    private Collection $Produit;

    public function __construct()
    {
        $this->Produit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->Produit->contains($produit)) {
            $this->Produit->add($produit);
            $produit->setWishList($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->Produit->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getWishList() === $this) {
                $produit->setWishList(null);
            }
        }

        return $this;
    }
}
