<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ObjetRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ObjetRepository::class)
 * @ORM\HasLifecycleCallbacks
 */


#[ApiResource(
    normalizationContext: ['groups' => ['objet']],
    // denormalizationContext: ['groups' => ['write']],
)]
class Objet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */

    #[Groups(['objet'])]

    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */

    #[Assert\NotBlank(message: "Veuillez entrer un nom pour l'objet")]
    #[Assert\Length(
        min: 2,
        max: 30,
        minMessage: 'Le nom doit faire plus de {{ limit }} caractères',
        maxMessage: 'Le nom doit faire {{ limit }} caractères maximum',
    )]

    #[Groups(['objet'])]

    private $denomination;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank(message: "Veuillez entrer une marque pour l'objet")]
    #[Assert\Length(
        min: 2,
        max: 30,
        minMessage: 'La marque doit faire plus de {{ limit }} caractères',
        maxMessage: 'La marque doit faire {{ limit }} caractères maximum',
    )]


    #[Groups(['objet'])]

    private $marque;

    /**
     * @ORM\Column(type="text")
     */

    #[Assert\NotBlank(message: "Veuillez entrer une description pour l'objet")]
    #[Assert\Length(
        min: 2,
        minMessage: 'La description doit faire plus de {{ limit }} caractères'
    )]

    #[Groups(['objet'])]

    private $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    #[Assert\NotBlank(message: "Veuillez entrer une valeur du neuf de l'objet")]
    #[Assert\Positive(message: "Veuillez entrer une valeur du neuf valide ")]

    #[Groups(['objet'])]

    private $valeur_achat;

    /**
     * @ORM\Column(type="integer")
     */

    #[Assert\NotNull(message: "Veuillez entrer un coefficient d'usure pour l'objet")]

    #[Groups(['objet'])]

    private $coef_usure;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    #[Assert\NotNull(message: "Veuillez entrer un pourcentage de calcul pour l'objet")]

    #[Groups(['objet'])]

    private $pourcent_calcul;

    /**
     * @ORM\Column(type="boolean")
     */
    #[Assert\NotNull(message: "Veuillez choisir de mettre ou non l'objet dans la vitrine")]
    #[Groups(['objet'])]

    private $vitrine;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="objets")
     */
    private $adherent;

    /**
     * @ORM\Column(type="date")
     */
    #[Groups(['objet'])]

    private $date_creation;

    /**
     * @ORM\OneToMany(targetEntity=Emprunt::class, mappedBy="objet", orphanRemoval=true)
     */

    #[Groups(['objet'])]

    private $emprunts;

    /**
     * @ORM\ManyToOne(targetEntity=SousCategorie::class, inversedBy="objets")
     *  @ORM\JoinColumn(nullable=false)
     */

    #[Assert\NotNull(message: "Veuillez choisir une sous-catégorie")]
    #[Groups(['objet'])]

    private $sous_categorie;

    /**
     * @ORM\Column(type="string", length=255)
     */

    #[Groups(['objet'])]

    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class, inversedBy="objets")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Assert\NotNull(message: "Veuillez choisir un lieu de stockage")]

    #[Groups(['objet'])]
    private $lieu;

    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="objet")
     */

    #[Groups(['objet'])]

    private $photos;

    /**
     * @ORM\Column(type="string", length=255)
     */

    #[Groups(['objet'])]

    private $statut;

    /**
     * @ORM\Column(type="text", nullable=true)
     */

    #[Groups(['objet'])]

    private $observation;

    /**
     * @ORM\ManyToOne(targetEntity=Catalogue::class, inversedBy="objets", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */

    #[Assert\NotNull(message: "Veuillez choisir un catalogue")]
    #[Groups(['objet'])]

    private $catalogue;

    /**
     * @ORM\Column(type="date", nullable=true)
     */

    #[Assert\Type("\DateTimeInterface", message: "Veuillez entrer une date de sortie de stock valide")]

    #[Groups(['objet'])]
    private $date_sortie_stock;

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
                $this->getMarque() .
                    time() .
                    hash('sha1', $this->getDenomination())
            );
        }
    }

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
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDenomination(): ?string
    {
        return $this->denomination;
    }

    public function setDenomination(string $denomination): self
    {
        $this->denomination = $denomination;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getValeurAchat(): ?string
    {
        return $this->valeur_achat;
    }

    public function setValeurAchat(string $valeur_achat): self
    {
        $this->valeur_achat = $valeur_achat;

        return $this;
    }

    public function getCoefUsure(): ?int
    {
        return $this->coef_usure;
    }

    public function setCoefUsure(int $coef_usure): self
    {
        $this->coef_usure = $coef_usure;

        return $this;
    }

    public function getPourcentCalcul(): ?string
    {
        return $this->pourcent_calcul;
    }

    public function setPourcentCalcul(string $pourcent_calcul): self
    {
        $this->pourcent_calcul = $pourcent_calcul;

        return $this;
    }

    public function getVitrine(): ?bool
    {
        return $this->vitrine;
    }

    public function setVitrine(bool $vitrine): self
    {
        $this->vitrine = $vitrine;

        return $this;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): self
    {
        $this->adherent = $adherent;

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
            $emprunt->setObjet($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): self
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getObjet() === $this) {
                $emprunt->setObjet(null);
            }
        }

        return $this;
    }

    public function getSousCategorie(): ?SousCategorie
    {
        return $this->sous_categorie;
    }

    public function setSousCategorie(?SousCategorie $sous_categorie): self
    {
        $this->sous_categorie = $sous_categorie;

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

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setObjet($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getObjet() === $this) {
                $photo->setObjet(null);
            }
        }

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): self
    {
        $this->observation = $observation;

        return $this;
    }

    public function getCatalogue(): ?Catalogue
    {
        return $this->catalogue;
    }

    public function setCatalogue(?Catalogue $catalogue): self
    {
        $this->catalogue = $catalogue;

        return $this;
    }

    public function getDateSortieStock(): ?\DateTimeInterface
    {
        return $this->date_sortie_stock;
    }

    public function setDateSortieStock(
        ?\DateTimeInterface $date_sortie_stock
    ): self {
        $this->date_sortie_stock = $date_sortie_stock;

        return $this;
    }
}