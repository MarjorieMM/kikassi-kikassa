<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LieuRepository::class)
 */
class Lieu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank(message: "Veuillez entrer un nom pour le lieu")]
    #[Assert\Length(
        min: 2,
        max: 30,
        minMessage: 'Le nom du lieu doit faire plus de {{ limit }} caractères',
        maxMessage: 'Le nom du lieu doit faire {{ limit }} caractères maximum',
    )]
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */

    #[Assert\NotBlank(message: "Veuillez entrer una adresse pour le lieu")]
    #[Assert\Length(
        min: 3,
        minMessage: 'L\'adresse du lieu doit faire plus de {{ limit }} caractères',

    )]
    private $adresse;

    /**
     * @ORM\Column(type="string", length=6)
     */
    #[Assert\NotBlank(message: "Veuillez entrer un code postal le lieu")]
    #[Assert\Length(
        min: 5,
        max: 6,
        minMessage: 'Veuillez enter un code postal valide',
        maxMessage: 'Veuillez enter un code postal valide',
    )]
    private $cp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank(message: "Veuillez entrer une ville pour le lieu")]
    #[Assert\Length(
        min: 2,
        max: 30,
        minMessage: 'La ville du lieu doit faire plus de {{ limit }} caractères',
        maxMessage: 'La ville du lieu doit faire {{ limit }} caractères maximum',
    )]
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     */

    #[Assert\NotBlank(message: "Veuillez entrer un numéro de téléphone pour le lieu")]
    #[Assert\Positive(message: "Veuillez entrer un numéro de téléphone valide ")]
    #[Assert\Length(
        min: 10,
        max: 10,
        exactMessage: "Veuillez entrer un numéro de téléphone valide (10 chiffres sans espace)"
    )]
    private $telephone;

    /**
     * @ORM\OneToMany(targetEntity=Objet::class, mappedBy="lieu")
     */
    private $objets;

    /**
     * @ORM\OneToMany(targetEntity=HoraireLieu::class, mappedBy="lieu", orphanRemoval=true)
     */
    private $horaire_lieu;

    /**
     * @ORM\Column(type="boolean")
     */
    private $fermeture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $raison_fermeture;

    public function __construct()
    {
        $this->objets = new ArrayCollection();
        $this->horaire_lieu = new ArrayCollection();
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection|Objet[]
     */
    public function getObjets(): Collection
    {
        return $this->objets;
    }

    public function addObjet(Objet $objet): self
    {
        if (!$this->objets->contains($objet)) {
            $this->objets[] = $objet;
            $objet->setLieu($this);
        }

        return $this;
    }

    public function removeObjet(Objet $objet): self
    {
        if ($this->objets->removeElement($objet)) {
            // set the owning side to null (unless already changed)
            if ($objet->getLieu() === $this) {
                $objet->setLieu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HoraireLieu[]
     */
    public function getHoraireLieu(): Collection
    {
        return $this->horaire_lieu;
    }

    public function addHoraireLieu(HoraireLieu $horaireLieu): self
    {
        if (!$this->horaire_lieu->contains($horaireLieu)) {
            $this->horaire_lieu[] = $horaireLieu;
            $horaireLieu->setLieu($this);
        }

        return $this;
    }

    public function removeHoraireLieu(HoraireLieu $horaireLieu): self
    {
        if ($this->horaire_lieu->removeElement($horaireLieu)) {
            // set the owning side to null (unless already changed)
            if ($horaireLieu->getLieu() === $this) {
                $horaireLieu->setLieu(null);
            }
        }

        return $this;
    }

    public function getFermeture(): ?bool
    {
        return $this->fermeture;
    }

    public function setFermeture(bool $fermeture): self
    {
        $this->fermeture = $fermeture;

        return $this;
    }

    public function getRaisonFermeture(): ?string
    {
        return $this->raison_fermeture;
    }

    public function setRaisonFermeture(?string $raison_fermeture): self
    {
        $this->raison_fermeture = $raison_fermeture;

        return $this;
    }
}
