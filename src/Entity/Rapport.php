<?php

namespace App\Entity;

use App\Repository\RapportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RapportRepository::class)]
class Rapport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Ce champ est obligatoire")]
    private ?string $action = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Ce champ est obligatoire")]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'rapport', targetEntity: DemandeMission::class)]
    private Collection $RapMiss;

    #[ORM\ManyToOne(inversedBy: 'Rapp')]
    private ?User $user = null;

    public function __construct()
    {
        $this->RapMiss = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, DemandeMission>
     */
    public function getRapMiss(): Collection
    {
        return $this->RapMiss;
    }

    public function addRapMiss(DemandeMission $rapMiss): self
    {
        if (!$this->RapMiss->contains($rapMiss)) {
            $this->RapMiss->add($rapMiss);
            $rapMiss->setRapport($this);
        }

        return $this;
    }
    public function addDemandeMission(DemandeMission $demandeMission): self
    {
        if (!$this->RapMiss->contains($demandeMission)) {
            $this->RapMiss[] = $demandeMission;
            $demandeMission->setRapport($this);
        }

        return $this;
    }

    public function removeRapMiss(DemandeMission $rapMiss): self
    {
        if ($this->RapMiss->removeElement($rapMiss)) {
            // set the owning side to null (unless already changed)
            if ($rapMiss->getRapport() === $this) {
                $rapMiss->setRapport(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    public function __toString()
    {
        return $this->action;
    }
}
