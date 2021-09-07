<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SuperAdminRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SuperAdminRepository::class)
 * @ORM\HasLifecycleCallbacks
 */

#[UniqueEntity('email', message: "L'adresse email existe déjà dans la base de données")]
class SuperAdmin implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */

    #[Groups(['person'])]
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank(message: "Veuillez entrer un nom")]
    #[Assert\Length(
        min: 2,
        max: 30,
        minMessage: 'Le nom doit faire plus de {{ limit }} caractères',
        maxMessage: 'Le nom doit faire {{ limit }} caractères maximum',
    )]

    #[Groups(['person'])]
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank(message: "Veuillez entrer un prénom")]
    #[Assert\Length(
        min: 2,
        max: 30,
        minMessage: 'Le prénom doit faire plus de {{ limit }} caractères',
        maxMessage: 'Le prénom doit faire {{ limit }} caractères maximum',
    )]

    #[Groups(['person'])]
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank(message: "Veuillez entrer une adresse email")]
    #[Assert\Email(
        message: 'Veuillez entrer un email valide',
    )]

    #[Groups(['person'])]
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank(message: "Veuillez entrer un mot de passe de 8 caractères au moins")]

    private $mot_de_passe;

    /**
     * @ORM\Column(type="date")
     */

    #[Groups(['person'])]
    private $date_creation;

    /**
     * @ORM\OneToMany(targetEntity=Emprunt::class, mappedBy="super_admin")
     */
    private $emprunts;

    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function updateDate()
    {
        if (empty($this->date_creation)) {
            $this->date_creation = new \DateTime();
        }
    }

    public function __construct()
    {
        $this->emprunts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getMotDePasse(): ?string
    {
        return $this->mot_de_passe;
    }

    public function setMotDePasse(string $mot_de_passe): self
    {
        $this->mot_de_passe = $mot_de_passe;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    /**
     * @return Collection|Emprunt[]
     */
    public function getEmprunts(): Collection
    {
        return $this->emprunts;
    }

    public function addEmprunt(Emprunt $emprunt): self
    {
        if (!$this->emprunts->contains($emprunt)) {
            $this->emprunts[] = $emprunt;
            $emprunt->setSuperAdmin($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): self
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getSuperAdmin() === $this) {
                $emprunt->setSuperAdmin(null);
            }
        }

        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function getPassword()
    {
        return $this->mot_de_passe;
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
    }

    // new to symfony 5.3, should remove getusername but get erroes when I do so
    public function getUserIdentifier()
    {
        return $this->email;
    }


    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_SUPER_ADMIN';

        return array_unique($roles);
    }
}