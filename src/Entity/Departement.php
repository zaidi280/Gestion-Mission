<?php

namespace App\Entity;

use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
class Departement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $dep = null;

    #[ORM\OneToMany(mappedBy: 'departement', targetEntity: Employe::class)]
    private Collection $empdep;

    public function __construct()
    {
        $this->empdep = new ArrayCollection();
    }

   

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDep(): ?string
    {
        return $this->dep;
    }

    public function setDep(string $dep): self
    {
        $this->dep = $dep;

        return $this;
    }

   

    public function __toString()
    {
        return $this->dep;
    }

    /**
     * @return Collection<int, Employe>
     */
    public function getEmpdep(): Collection
    {
        return $this->empdep;
    }

    public function addEmpdep(Employe $empdep): self
    {
        if (!$this->empdep->contains($empdep)) {
            $this->empdep->add($empdep);
            $empdep->setDepartement($this);
        }

        return $this;
    }

    public function removeEmpdep(Employe $empdep): self
    {
        if ($this->empdep->removeElement($empdep)) {
            // set the owning side to null (unless already changed)
            if ($empdep->getDepartement() === $this) {
                $empdep->setDepartement(null);
            }
        }

        return $this;
    }
}
