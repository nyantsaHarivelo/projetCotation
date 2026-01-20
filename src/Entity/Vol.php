<?php

namespace App\Entity;

use App\Repository\VolRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolRepository::class)]
class Vol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $reference = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $primus = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $terminus = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $date_primus = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $date_terminus = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $agence_vol = null;

    #[ORM\ManyToOne(inversedBy: 'vols')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Voyage $voyage = null;

    #[ORM\Column(nullable: true)]
    private ?float $cout_vol = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPrimus(): ?string
    {
        return $this->primus;
    }

    public function setPrimus(?string $primus): static
    {
        $this->primus = $primus;

        return $this;
    }

    public function getTerminus(): ?string
    {
        return $this->terminus;
    }

    public function setTerminus(?string $terminus): static
    {
        $this->terminus = $terminus;

        return $this;
    }

    public function getDatePrimus(): ?\DateTime
    {
        return $this->date_primus;
    }

    public function setDatePrimus(?\DateTime $date_primus): static
    {
        $this->date_primus = $date_primus;

        return $this;
    }

    public function getDateTerminus(): ?\DateTime
    {
        return $this->date_terminus;
    }

    public function setDateTerminus(?\DateTime $date_terminus): static
    {
        $this->date_terminus = $date_terminus;

        return $this;
    }

    public function getAgenceVol(): ?string
    {
        return $this->agence_vol;
    }

    public function setAgenceVol(?string $agence_vol): static
    {
        $this->agence_vol = $agence_vol;

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

    public function getCoutVol(): ?float
    {
        return $this->cout_vol;
    }

    public function setCoutVol(float $cout_vol): static
    {
        $this->cout_vol = $cout_vol;

        return $this;
    }
}
