<?php

namespace App\Entity;

use App\Repository\TransportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransportRepository::class)]
class Transport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $date_primus = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $date_terminus = null;

    #[ORM\Column(length: 50)]
    private ?string $primus = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $terminus = null;

    #[ORM\Column(nullable: true)]
    private ?float $nombre_km = null;

    #[ORM\Column(nullable: true)]
    private ?float $nombre_litre = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_litre = null;

    #[ORM\Column(nullable: true)]
    private ?float $cout_transport = null;

    #[ORM\ManyToOne(inversedBy: 'transports')]
    private ?voyage $voyage = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrimus(): ?string
    {
        return $this->primus;
    }

    public function setPrimus(string $primus): static
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

    public function getNombreKm(): ?float
    {
        return $this->nombre_km;
    }

    public function setNombreKm(?float $nombre_km): static
    {
        $this->nombre_km = $nombre_km;

        return $this;
    }

    public function getNombreLitre(): ?float
    {
        return $this->nombre_litre;
    }

    public function setNombreLitre(?float $nombre_litre): static
    {
        $this->nombre_litre = $nombre_litre;

        return $this;
    }

    public function getPrixLitre(): ?float
    {
        return $this->prix_litre;
    }

    public function setPrixLitre(?float $prix_litre): static
    {
        $this->prix_litre = $prix_litre;

        return $this;
    }

    public function getCoutTransport(): ?float
    {
        return $this->cout_transport;
    }

    public function setCoutTransport(?float $cout_transport): static
    {
        $this->cout_transport = $cout_transport;

        return $this;
    }

    public function getVoyage(): ?voyage
    {
        return $this->voyage;
    }

    public function setVoyage(?voyage $voyage): static
    {
        $this->voyage = $voyage;

        return $this;
    }
}
