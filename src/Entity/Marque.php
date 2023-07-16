<?php

namespace App\Entity;

use App\Repository\MarqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarqueRepository::class)]
class Marque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $marques = null;

    #[ORM\OneToMany(mappedBy: 'marque', targetEntity: Voiture::class)]
    private Collection $vehicules;

    public function __construct()
    {
        $this->vehicules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarques(): ?string
    {
        return $this->marques;
    }

    public function setMarques(string $marques): self
    {
        $this->marques = $marques;

        return $this;
    }

    /**
     * @return Collection<int, Voiture>
     */
    public function getVehicules(): Collection
    {
        return $this->vehicules;
    }

    public function addVehicule(Voiture $vehicule): self
    {
        if (!$this->vehicules->contains($vehicule)) {
            $this->vehicules->add($vehicule);
            $vehicule->setMarque($this);
        }

        return $this;
    }

    public function removeVehicule(Voiture $vehicule): self
    {
        if ($this->vehicules->removeElement($vehicule)) {
            // set the owning side to null (unless already changed)
            if ($vehicule->getMarque() === $this) {
                $vehicule->setMarque(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->marques;
    }
}
