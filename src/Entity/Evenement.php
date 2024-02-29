<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"The product name is required.")]
    private ?string $titreEvenement = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"Please add a discription.")]
    private ?string $descriptionEvenement = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Produit $produit = null;

    #[ORM\OneToOne(mappedBy: 'evenement', cascade: ['persist', 'remove'])]
    private ?ParticipationEvenement $participationEvenement = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Please add your product's image address.")]
    #[Assert\Url(message:"You must provide us with the image address.")]
    private ?string $imgLink = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreEvenement(): ?string
    {
        return $this->titreEvenement;
    }

    public function setTitreEvenement(string $titreEvenement): static
    {
        $this->titreEvenement = $titreEvenement;

        return $this;
    }

    public function getDescriptionEvenement(): ?string
    {
        return $this->descriptionEvenement;
    }

    public function setDescriptionEvenement(string $descriptionEvenement): static
    {
        $this->descriptionEvenement = $descriptionEvenement;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getParticipationEvenement(): ?ParticipationEvenement
    {
        return $this->participationEvenement;
    }

    public function setParticipationEvenement(ParticipationEvenement $participationEvenement): static
    {
        // set the owning side of the relation if necessary
        if ($participationEvenement->getEvenement() !== $this) {
            $participationEvenement->setEvenement($this);
        }

        $this->participationEvenement = $participationEvenement;

        return $this;
    }

    public function getImgLink(): ?string
    {
        return $this->imgLink;
    }

    public function setImgLink(string $imgLink): static
    {
        $this->imgLink = $imgLink;

        return $this;
    }
}
