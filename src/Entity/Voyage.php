<?php

namespace App\Entity;

use App\Repository\VoyageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoyageRepository::class)]
class Voyage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $tourist = null;

    #[ORM\Column]
    private ?bool $acheve = null;

    #[ORM\Column]
    private ?bool $paye = null;

    #[ORM\Column]
    private ?\DateTime $date_debut = null;

    /**
     * @var Collection<int, Prestation>
     */
    #[ORM\OneToMany(targetEntity: Prestation::class, mappedBy: 'voyage')]
    private Collection $prestations;

    /**
     * @var Collection<int, Hebergement>
     */
    #[ORM\OneToMany(targetEntity: Hebergement::class, mappedBy: 'Voyage', orphanRemoval: true)]
    private Collection $hebergements;

    /**
     * @var Collection<int, Vol>
     */
    #[ORM\OneToMany(targetEntity: Vol::class, mappedBy: 'voyage', orphanRemoval: true)]
    private Collection $vols;

    /**
     * @var Collection<int, Transport>
     */
    #[ORM\OneToMany(targetEntity: Transport::class, mappedBy: 'voyage')]
    private Collection $transports;

    #[ORM\ManyToOne(inversedBy: 'voyages')]
    #[ORM\JoinColumn(nullable: true)]
    private ?user $editUser = null;

    public function __construct()
    {
        $this->prestations = new ArrayCollection();
        $this->hebergements = new ArrayCollection();
        $this->vols = new ArrayCollection();
        $this->transports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTourist(): ?string
    {
        return $this->tourist;
    }

    public function setTourist(string $tourist): static
    {
        $this->tourist = $tourist;

        return $this;
    }

    public function isAcheve(): ?bool
    {
        return $this->acheve;
    }

    public function setAcheve(bool $acheve): static
    {
        $this->acheve = $acheve;

        return $this;
    }

    public function isPaye(): ?bool
    {
        return $this->paye;
    }

    public function setPaye(bool $paye): static
    {
        $this->paye = $paye; 

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

    /**
     * @return Collection<int, Prestation>
     */
    public function getPrestations(): Collection
    {
        return $this->prestations;
    }

    public function addPrestation(Prestation $prestation): static
    {
        if (!$this->prestations->contains($prestation)) {
            $this->prestations->add($prestation);
            $prestation->setVoyage($this);
        }

        return $this;
    }

    public function removePrestation(Prestation $prestation): static
    {
        if ($this->prestations->removeElement($prestation)) {
            // set the owning side to null (unless already changed)
            if ($prestation->getVoyage() === $this) {
                $prestation->setVoyage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Hebergement>
     */
    public function getHebergements(): Collection
    {
        return $this->hebergements;
    }

    public function addHebergement(Hebergement $hebergement): static
    {
        if (!$this->hebergements->contains($hebergement)) {
            $this->hebergements->add($hebergement);
            $hebergement->setVoyage($this);
        }

        return $this;
    }

    public function removeHebergement(Hebergement $hebergement): static
    {
        if ($this->hebergements->removeElement($hebergement)) {
            // set the owning side to null (unless already changed)
            if ($hebergement->getVoyage() === $this) {
                $hebergement->setVoyage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Vol>
     */
    public function getVols(): Collection
    {
        return $this->vols;
    }

    public function addVol(Vol $vol): static
    {
        if (!$this->vols->contains($vol)) {
            $this->vols->add($vol);
            $vol->setVoyage($this);
        }

        return $this;
    }

    public function removeVol(Vol $vol): static
    {
        if ($this->vols->removeElement($vol)) {
            // set the owning side to null (unless already changed)
            if ($vol->getVoyage() === $this) {
                $vol->setVoyage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transport>
     */
    public function getTransports(): Collection
    {
        return $this->transports;
    }

    public function addTransport(Transport $transport): static
    {
        if (!$this->transports->contains($transport)) {
            $this->transports->add($transport);
            $transport->setVoyage($this);
        }

        return $this;
    }

    public function removeTransport(Transport $transport): static
    {
        if ($this->transports->removeElement($transport)) {
            // set the owning side to null (unless already changed)
            if ($transport->getVoyage() === $this) {
                $transport->setVoyage(null);
            }
        }

        return $this;
    }

    public function getEditUser(): ?user
    {
        return $this->editUser;
    }

    public function setEditUser(?user $editUser): static
    {
        $this->editUser = $editUser;

        return $this;
    }
}
