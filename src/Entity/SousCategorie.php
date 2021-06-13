<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SousCategorieRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SousCategorieRepository::class)
 * @ORM\HasLifecycleCallbacks
 */

#[ApiResource(
    normalizationContext: ['groups' => ['objet', 'categorie']],
    // denormalizationContext: ['groups' => ['write']],
)]
class SousCategorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['categorie', 'objet'])]

    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['categorie', 'objet'])]

    private $nom_ss_categorie;

    /**
     * @ORM\OneToMany(targetEntity=Objet::class, mappedBy="sous_categorie")
     */

    private $objets;

    /**
     * @ORM\Column(type="string", length=255)
     */

    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="sousCategories")
     * @ORM\JoinColumn(nullable=false)
     */
    // #[Groups(['objet'])]

    private $categorie;

    /**
     *
     *@ORM\PrePersist
     *
     * @return void
     */
    public function initSlug()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify(
                $this->getNomSsCategorie() .
                    time() .
                    hash('sha1', $this->getNomSsCategorie())
            );
        }
    }

    public function __construct()
    {
        $this->objets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSsCategorie(): ?string
    {
        return $this->nom_ss_categorie;
    }

    public function setNomSsCategorie(string $nom_ss_categorie): self
    {
        $this->nom_ss_categorie = $nom_ss_categorie;

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
            $objet->setSousCategorie($this);
        }

        return $this;
    }

    public function removeObjet(Objet $objet): self
    {
        if ($this->objets->removeElement($objet)) {
            // set the owning side to null (unless already changed)
            if ($objet->getSousCategorie() === $this) {
                $objet->setSousCategorie(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
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
}