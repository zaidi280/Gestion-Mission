<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Ce champ est obligatoire")]
    private ?string $Matricule = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Ce champ est obligatoire")]
    private ?string $model = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Ce champ est obligatoire")]
    private ?string $couleur = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;
    #[ORM\Column]
    private $supprime = false;
    #[ORM\ManyToOne(inversedBy: 'Voitures')]
    #[Assert\NotBlank(message: "Ce champ est obligatoire")]
    private ?Categorie $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'vehicules')]
    #[Assert\NotBlank(message: "Ce champ est obligatoire")]
    private ?Marque $marque = null;

    #[ORM\OneToMany(mappedBy: 'voiture', targetEntity: DemandeMission::class)]
    private Collection $affMiss;

    public function __construct()
    {
        $this->affMiss = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getMatricule(): ?string
    {
        return $this->Matricule;
    }

    public function setMatricule(?string $Matricule): self
    {
        $this->Matricule = $Matricule;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
    public function isSuprrimer(): ?bool
    {
        return $this->supprime;
    }

    public function setSuprrimer(bool $supprime): void
    {
        $this->supprime = $supprime;
    }
    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * @return Collection<int, DemandeMission>
     */
    public function getAffMiss(): Collection
    {
        return $this->affMiss;
    }

    public function addAffMiss(DemandeMission $affMiss): self
    {
        if (!$this->affMiss->contains($affMiss)) {
            $this->affMiss->add($affMiss);
            $affMiss->setVoiture($this);
        }

        return $this;
    }

    public function removeAffMiss(DemandeMission $affMiss): self
    {
        if ($this->affMiss->removeElement($affMiss)) {
            // set the owning side to null (unless already changed)
            if ($affMiss->getVoiture() === $this) {
                $affMiss->setVoiture(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return 'Marque :' . $this->marque . ' / Model :' . $this->model . ' / Matricule :' . $this->Matricule;
    }
}
