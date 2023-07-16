<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
#[UniqueEntity('user', message: 'utilisateur existe deja')]
#[UniqueEntity(fields: ['NumTell'], message: 'Ce numéro de téléphone existe déjà.')]
#[UniqueEntity(fields: ['NumFax'], message: 'Ce numéro de fax existe déjà.')]
#[UniqueEntity(fields: ['numcarte'], message: 'Ce numéro de cin existe déjà.')]
#[UniqueEntity(fields: ['numcompte'], message: 'Ce numéro de compte courant existe déjà.')]
class Employe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $Nom_Prenom = null;
    #[ORM\Column(type: Types::BIGINT)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^[0-9]{8}$/', message: 'il faut saisie 8 entier exactement.')]
    private ?string $NumTell = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^[0-9]{8}$/', message: 'il faut saisie 8 entier exactement.')]
    private ?string $NumFax = null;

    #[ORM\ManyToMany(targetEntity: DemandeMission::class, mappedBy: 'Employes')]
    private Collection $demandeMissions;

    #[ORM\OneToMany(mappedBy: 'employe', targetEntity: DemandeMission::class)]
    private Collection $missions;

    #[ORM\Column]
    private ?bool $supprimer = false;

    #[ORM\ManyToOne(inversedBy: 'employe')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'empgrade')]
    #[Assert\NotBlank]
    private ?Profession $profession = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^[0-9]{8}$/', message: 'il faut saisie 8 entier exactement.')]
    private ?string $numcarte = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{20}$/', message: 'il faut saisir 20 entier exactement.')]
    private ?string $numcompte = null;

    #[ORM\ManyToOne(inversedBy: 'empdep')]
    private ?Departement $departement = null;
    public function __construct()
    {
        $this->demandeMissions = new ArrayCollection();
        $this->missions = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNumTell(): ?string
    {
        return $this->NumTell;
    }

    public function setNumTell(string $NumTell): self
    {
        $this->NumTell = $NumTell;

        return $this;
    }

    public function getNumFax(): ?string
    {
        return $this->NumFax;
    }

    public function setNumFax(string $NumFax): self
    {
        $this->NumFax = $NumFax;

        return $this;
    }

    public function getNomPrenom(): ?string
    {
        return $this->Nom_Prenom;
    }

    public function setNomPrenom(string $Nom_Prenom): self
    {
        $this->Nom_Prenom = $Nom_Prenom;

        return $this;
    }
    public function __toString()
    {
        return $this->Nom_Prenom;
    }

    /**
     * @return Collection<int, DemandeMission>
     */
    public function getDemandeMissions(): Collection
    {
        return $this->demandeMissions;
    }

    public function addDemandeMission(DemandeMission $demandeMission): self
    {
        if (!$this->demandeMissions->contains($demandeMission)) {
            $this->demandeMissions->add($demandeMission);
            $demandeMission->addEmploye($this);
        }

        return $this;
    }

    public function removeDemandeMission(DemandeMission $demandeMission): self
    {
        if ($this->demandeMissions->removeElement($demandeMission)) {
            $demandeMission->removeEmploye($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, DemandeMission>
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(DemandeMission $mission): self
    {
        if (!$this->missions->contains($mission)) {
            $this->missions->add($mission);
            $mission->setEmploye($this);
        }

        return $this;
    }

    public function removeMission(DemandeMission $mission): self
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getEmploye() === $this) {
                $mission->setEmploye(null);
            }
        }

        return $this;
    }

    public function isSupprimer(): ?bool
    {
        return $this->supprimer;
    }

    public function setSupprimer(bool $supprimer): self
    {
        $this->supprimer = $supprimer;

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

    public function getProfession(): ?Profession
    {
        return $this->profession;
    }

    public function setProfession(?Profession $profession): self
    {
        $this->profession = $profession;

        return $this;
    }


    public function getNumcarte(): ?string
    {
        return $this->numcarte;
    }

    public function setNumcarte(string $numcarte): self
    {
        $this->numcarte = $numcarte;

        return $this;
    }

    public function getNumcompte(): ?string
    {
        return $this->numcompte;
    }

    public function setNumcompte(string $numcompte): self
    {
        $this->numcompte = $numcompte;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }
}
