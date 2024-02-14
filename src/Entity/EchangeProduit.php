<?php

namespace App\Entity;

use App\Repository\EchangeProduitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EchangeProduitRepository::class)]
class EchangeProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produitIn = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produitOut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_echange = null;

    #[ORM\Column(nullable: true)]
    private ?bool $valide = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduitIn(): ?Produit
    {
        return $this->produitIn;
    }

    public function setProduitIn(Produit $produitIn): static
    {
        $this->produitIn = $produitIn;

        return $this;
    }

    public function getProduitOut(): ?Produit
    {
        return $this->produitOut;
    }

    public function setProduitOut(Produit $produitOut): static
    {
        $this->produitOut = $produitOut;

        return $this;
    }

    public function getDateEchange(): ?\DateTimeInterface
    {
        return $this->date_echange;
    }

    public function setDateEchange(\DateTimeInterface $date_echange): static
    {
        $this->date_echange = $date_echange;

        return $this;
    }

    public function isValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(?bool $valide): static
    {
        $this->valide = $valide;

        return $this;
    }
}
