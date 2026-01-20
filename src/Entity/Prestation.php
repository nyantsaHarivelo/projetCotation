<?php

namespace App\Entity;

use App\Repository\PrestationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrestationRepository::class)]
class Prestation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $prestation = null;

    #[ORM\Column]
    private ?float $coutPrestation = null;

    #[ORM\Column]
    private ?\DateTime $datePrestation = null;

    #[ORM\ManyToOne(inversedBy: 'prestations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Voyage $voyage = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $lieu_prestation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrestation(): ?string
    {
        return $this->prestation;
    }

    public function setPrestation(string $prestation): static
    {
        $this->prestation = $prestation;

        return $this;
    }

    public function getCoutPrestation(): ?float
    {
        return $this->coutPrestation;
    }

    public function setCoutPrestation(float $coutPrestation): static
    {
        $this->coutPrestation = $coutPrestation;

        return $this;
    }

    public function getDatePrestation(): ?\DateTime
    {
        return $this->datePrestation;
    }

    public function setDatePrestation(\DateTime $datePrestation): static
    {
        $this->datePrestation = $datePrestation;

        return $this;
    }

    public function getVoyage(): ?Voyage
    {
        return $this->voyage;
    }

    public function setVoyage(?Voyage $voyage): static
    {
        $this->voyage = $voyage;

        return $this;
    }

    public function getLieuPrestation(): ?string
    {
        return $this->lieu_prestation;
    }

    public function setLieuPrestation(?string $lieu_prestation): static
    {
        $this->lieu_prestation = $lieu_prestation;

        return $this;
    }
}
