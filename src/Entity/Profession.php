<?php

namespace App\Entity;

use App\Repository\ProfessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfessionRepository::class)]
class Profession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $grade = null;

    #[ORM\Column]
    private ?int $montant = null;

    #[ORM\OneToMany(mappedBy: 'profession', targetEntity: Employe::class)]
    private Collection $empgrade;



    public function __construct()
    {

        $this->empgrade = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(string $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function __toString()
    {
        return $this->grade;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * @return Collection<int, Employe>
     */
    public function getEmpgrade(): Collection
    {
        return $this->empgrade;
    }

    public function addEmpgrade(Employe $empgrade): self
    {
        if (!$this->empgrade->contains($empgrade)) {
            $this->empgrade->add($empgrade);
            $empgrade->setProfession($this);
        }

        return $this;
    }

    public function removeEmpgrade(Employe $empgrade): self
    {
        if ($this->empgrade->removeElement($empgrade)) {
            // set the owning side to null (unless already changed)
            if ($empgrade->getProfession() === $this) {
                $empgrade->setProfession(null);
            }
        }

        return $this;
    }
}
