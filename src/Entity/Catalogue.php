<?php

namespace App\Entity;

use App\Repository\CatalogueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CatalogueRepository::class)
 */
class Catalogue
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
    #[Assert\NotBlank(message:"Veuillez entrer un nom pour le catalogue")]

    private $nom_catalogue;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotNull(message:"Veuillez choisir une catégorie de fourmi")]

    private $categorie_fourmi;

    /**
     * @ORM\OneToMany(targetEntity=Objet::class, mappedBy="catalogue")
     */
    private $objets;

    public function __construct()
    {
        $this->objets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCatalogue(): ?string
    {
        return $this->nom_catalogue;
    }

    public function setNomCatalogue(string $nom_catalogue): self
    {
        $this->nom_catalogue = $nom_catalogue;

        return $this;
    }

    public function getCategorieFourmi(): ?string
    {
        return $this->categorie_fourmi;
    }

    public function setCategorieFourmi(string $categorie_fourmi): self
    {
        $this->categorie_fourmi = $categorie_fourmi;

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
            $objet->setCatalogue($this);
        }

        return $this;
    }

    public function removeObjet(Objet $objet): self
    {
        if ($this->objets->removeElement($objet)) {
            // set the owning side to null (unless already changed)
            if ($objet->getCatalogue() === $this) {
                $objet->setCatalogue(null);
            }
        }

        return $this;
    }
}