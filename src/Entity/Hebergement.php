<?php

namespace App\Entity;

use App\Repository\HebergementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HebergementRepository::class)]
class Hebergement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom_hebergement = null;

    #[ORM\Column]
    private ?\DateTime $date_debut = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $date_fin = null;

    #[ORM\Column(length: 50)]
    private ?string $lieu_hebergement = null;

    #[ORM\Column]
    private ?float $cout_hebergement = null;

    #[ORM\ManyToOne(inversedBy: 'hebergements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?voyage $Voyage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomHebergement(): ?string
    {
        return $this->nom_hebergement;
    }

    public function setNomHebergement(string $nom_hebergement): static
    {
        $this->nom_hebergement = $nom_hebergement;

        return $this;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTime $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->date_fin;
    }

    public function setDateFin(?\DateTime $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getLieuHebergement(): ?string
    {
        return $this->lieu_hebergement;
    }

    public function setLieuHebergement(string $lieu_hebergement): static
    {
        $this->lieu_hebergement = $lieu_hebergement;

        return $this;
    }

    public function getCoutHebergement(): ?float
    {
        return $this->cout_hebergement;
    }

    public function setCoutHebergement(float $cout_hebergement): static
    {
        $this->cout_hebergement = $cout_hebergement;

        return $this;
    }

    public function getVoyage(): ?voyage
    {
        return $this->Voyage;
    }

    public function setVoyage(?voyage $Voyage): static
    {
        $this->Voyage = $Voyage;

        return $this;
    }
}
