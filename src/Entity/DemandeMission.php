<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use App\Entity\Employe;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\DemandeMissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

#[ORM\Entity(repositoryClass: DemandeMissionRepository::class)]

class DemandeMission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numMiss = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DateCreation = null;
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Ce champ est obligatoire")]
    private ?string $Sujet = null;
    #[ORM\Column]
    private $supprime = false;
    #[ORM\ManyToOne(inversedBy: 'DemMiss')]
    #[Assert\NotBlank]
    private ?Categorie $categorie = null;
    #[ORM\ManyToOne(inversedBy: 'missions')]
    #[Assert\NotBlank]
    private ?Destination $destination = null;

    #[ORM\ManyToOne(inversedBy: 'affMiss')]
    private ?Voiture $voiture = null;

    #[ORM\ManyToMany(targetEntity: Employe::class, inversedBy: 'demandeMissions')]
    private Collection $Employes;

    #[ORM\ManyToOne(inversedBy: 'missions')]
    private ?Employe $employe = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Ce champ est obligatoire")]
    private ?string $materiel = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "Ce champ est obligatoire")]
    #[GreaterThanOrEqual(propertyPath: "DateCreation", message: "La date de création doit être supérieure ou égale à la date de début.")]
    private ?\DateTimeInterface $DateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "Ce champ est obligatoire")]
    #[GreaterThan(propertyPath: "DateDebut", message: "La date de fin doit être supérieure à la date de début.")]
    private ?\DateTimeInterface $DateFin = null;

    #[ORM\ManyToOne(inversedBy: 'Nomdem')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'RapMiss')]
    private ?Rapport $rapport = null;

    #[ORM\Column]
    private ?bool $valider = false;

    #[ORM\Column]
    private ?bool $ordvalid = false;




    #[ORM\Column]
    private ?bool $payer = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $accompext = null;



    public function __construct()
    {
        $this->Employes = new ArrayCollection();
        $this->DateCreation = new \DateTime();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumMiss(): ?string
    {
        return $this->numMiss;
    }

    public function setNumMiss(string $numMiss): self
    {
        $this->numMiss = $numMiss;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->DateCreation;
    }

    public function setDateCreation(?DateTimeInterface $DateCreation): self
    {
        $this->DateCreation = $DateCreation;

        return $this;
    }
    public function getSujet(): ?string
    {
        return $this->Sujet;
    }

    public function setSujet(?string $Sujet): self
    {
        $this->Sujet = $Sujet;

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
    public function getDestination(): ?Destination
    {
        return $this->destination;
    }
    public function setDestination(?Destination $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getVoiture(): ?Voiture
    {
        return $this->voiture;
    }

    public function setVoiture(?Voiture $voiture): self
    {
        $this->voiture = $voiture;

        return $this;
    }

    /**
     * @return Collection<int, Employe>
     */
    public function getEmployes(): Collection
    {
        return $this->Employes;
    }

    public function addEmploye(Employe $employe): self
    {
        if (!$this->Employes->contains($employe)) {
            $this->Employes->add($employe);
        }

        return $this;
    }

    public function removeEmploye(Employe $employe): self
    {
        $this->Employes->removeElement($employe);

        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): self
    {
        $this->employe = $employe;

        return $this;
    }

    public function getMateriel(): ?string
    {
        return $this->materiel;
    }

    public function setMateriel(?string $materiel): self
    {
        $this->materiel = $materiel;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->DateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $DateDebut): self
    {
        $this->DateDebut = $DateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->DateFin;
    }

    public function setDateFin(?\DateTimeInterface $DateFin): self
    {
        $this->DateFin = $DateFin;

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

    public function getRapport(): ?Rapport
    {
        return $this->rapport;
    }

    public function setRapport(?Rapport $rapport): self
    {
        $this->rapport = $rapport;

        return $this;
    }

    public function isValider(): ?bool
    {
        return $this->valider;
    }

    public function setValider(bool $valider): self
    {
        $this->valider = $valider;

        return $this;
    }

    public function isOrdvalid(): ?bool
    {
        return $this->ordvalid;
    }

    public function setOrdvalid(bool $ordvalid): self
    {
        $this->ordvalid = $ordvalid;

        return $this;
    }

    public function isPayer(): ?bool
    {
        return $this->payer;
    }

    public function setPayer(bool $payer): self
    {
        $this->payer = $payer;

        return $this;
    }

    public function getAccompext(): ?string
    {
        return $this->accompext;
    }

    public function setAccompext(string $accompext): self
    {
        $this->accompext = $accompext;

        return $this;
    }
}
