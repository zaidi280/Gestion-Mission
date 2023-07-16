<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Voiture::class)]
    private Collection $Voitures;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: DemandeMission::class)]
    private Collection $DemMiss;

    public function __construct()
    {
        $this->Voitures = new ArrayCollection();
        $this->DemMiss = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Voiture>
     */
    public function getVoitures(): Collection
    {
        return $this->Voitures;
    }

    public function addVoiture(Voiture $voiture): self
    {
        if (!$this->Voitures->contains($voiture)) {
            $this->Voitures->add($voiture);
            $voiture->setCategorie($this);
        }

        return $this;
    }

    public function removeVoiture(Voiture $voiture): self
    {
        if ($this->Voitures->removeElement($voiture)) {
            // set the owning side to null (unless already changed)
            if ($voiture->getCategorie() === $this) {
                $voiture->setCategorie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DemandeMission>
     */
    public function getDemMiss(): Collection
    {
        return $this->DemMiss;
    }

    public function addDemMiss(DemandeMission $demMiss): self
    {
        if (!$this->DemMiss->contains($demMiss)) {
            $this->DemMiss->add($demMiss);
            $demMiss->setCategorie($this);
        }

        return $this;
    }

    public function removeDemMiss(DemandeMission $demMiss): self
    {
        if ($this->DemMiss->removeElement($demMiss)) {
            // set the owning side to null (unless already changed)
            if ($demMiss->getCategorie() === $this) {
                $demMiss->setCategorie(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->type;
    }
}
