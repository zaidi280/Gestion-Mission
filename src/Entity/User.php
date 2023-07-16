<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Adresse email deja existe')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^[^@\s]+@[^@\s]+\.[^@\s]+$/', message: 'Le-mail {{ value }} nest pas un e-mail valide.')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DemandeMission::class)]
    private Collection $Nomdem;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Rapport::class)]
    private Collection $Rapp;

    #[ORM\Column]
    private ?bool $supprimer = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Employe::class)]
    private Collection $employe;

    public function __construct()
    {
        $this->Nomdem = new ArrayCollection();
        $this->Rapp = new ArrayCollection();
        $this->employe = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, DemandeMission>
     */
    public function getNomdem(): Collection
    {
        return $this->Nomdem;
    }

    public function addNomdem(DemandeMission $nomdem): self
    {
        if (!$this->Nomdem->contains($nomdem)) {
            $this->Nomdem->add($nomdem);
            $nomdem->setUser($this);
        }

        return $this;
    }

    public function removeNomdem(DemandeMission $nomdem): self
    {
        if ($this->Nomdem->removeElement($nomdem)) {
            // set the owning side to null (unless already changed)
            if ($nomdem->getUser() === $this) {
                $nomdem->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rapport>
     */
    public function getRapp(): Collection
    {
        return $this->Rapp;
    }

    public function addRapp(Rapport $rapp): self
    {
        if (!$this->Rapp->contains($rapp)) {
            $this->Rapp->add($rapp);
            $rapp->setUser($this);
        }

        return $this;
    }

    public function removeRapp(Rapport $rapp): self
    {
        if ($this->Rapp->removeElement($rapp)) {
            // set the owning side to null (unless already changed)
            if ($rapp->getUser() === $this) {
                $rapp->setUser(null);
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

    /**
     * @return Collection<int, Employe>
     */
    public function getEmploye(): Collection
    {
        return $this->employe;
    }

    public function addEmploye(Employe $employe): self
    {
        if (!$this->employe->contains($employe)) {
            $this->employe->add($employe);
            $employe->setUser($this);
        }

        return $this;
    }

    public function removeEmploye(Employe $employe): self
    {
        if ($this->employe->removeElement($employe)) {
            // set the owning side to null (unless already changed)
            if ($employe->getUser() === $this) {
                $employe->setUser(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->id;
    }
}
