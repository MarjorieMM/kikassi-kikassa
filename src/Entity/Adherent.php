<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdherentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=AdherentRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Adherent
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="date")
     */
    private $date_naissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieu_naissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categorie_fourmi;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $montant_cotisation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $moyen_paiement;

    /**
     * @ORM\Column(type="date")
     */
    private $date_adhesion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $compte_actif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $admin;

    /**
     * @ORM\OneToOne(targetEntity=AdhesionBibliotheque::class, mappedBy="adherent", cascade={"persist", "remove"})
     */
    private $adhesionBibliotheque;

    /**
     * @ORM\OneToMany(targetEntity=Objet::class, mappedBy="adherent")
     */
    private $objets;

    /**
     * @ORM\OneToMany(targetEntity=Emprunt::class, mappedBy="adherent", orphanRemoval=true)
     */
    private $emprunts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat_cotisation;

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
            $this->slug = $slugify->slugify($this->getNom().time().hash('sha1', $this->getPrenom()));
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
        if (empty($this->date_adhesion)) {
            $this->date_adhesion = new \DateTime();
        }
    }

    public function __construct()
    {
        $this->objets = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getLieuNaissance(): ?string
    {
        return $this->lieu_naissance;
    }

    public function setLieuNaissance(string $lieu_naissance): self
    {
        $this->lieu_naissance = $lieu_naissance;

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

    public function getMontantCotisation(): ?int
    {
        return $this->montant_cotisation;
    }

    public function setMontantCotisation(int $montant_cotisation): self
    {
        $this->montant_cotisation = $montant_cotisation;

        return $this;
    }

    public function getMoyenPaiement(): ?string
    {
        return $this->moyen_paiement;
    }

    public function setMoyenPaiement(string $moyen_paiement): self
    {
        $this->moyen_paiement = $moyen_paiement;

        return $this;
    }

    public function getDateAdhesion(): ?\DateTimeInterface
    {
        return $this->date_adhesion;
    }

    public function setDateAdhesion(\DateTimeInterface $date_adhesion): self
    {
        $this->date_adhesion = $date_adhesion;

        return $this;
    }

    public function getCompteActif(): ?bool
    {
        return $this->compte_actif;
    }

    public function setCompteActif(bool $compte_actif): self
    {
        $this->compte_actif = $compte_actif;

        return $this;
    }

    public function getAdmin(): ?bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getAdhesionBibliotheque(): ?AdhesionBibliotheque
    {
        return $this->adhesionBibliotheque;
    }

    public function setAdhesionBibliotheque(AdhesionBibliotheque $adhesionBibliotheque): self
    {
        // set the owning side of the relation if necessary
        if ($adhesionBibliotheque->getAdherent() !== $this) {
            $adhesionBibliotheque->setAdherent($this);
        }

        $this->adhesionBibliotheque = $adhesionBibliotheque;

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
            $objet->setAdherent($this);
        }

        return $this;
    }

    public function removeObjet(Objet $objet): self
    {
        if ($this->objets->removeElement($objet)) {
            // set the owning side to null (unless already changed)
            if ($objet->getAdherent() === $this) {
                $objet->setAdherent(null);
            }
        }

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
            $emprunt->setAdherent($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): self
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getAdherent() === $this) {
                $emprunt->setAdherent(null);
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

    public function getEtatCotisation(): ?string
    {
        return $this->etat_cotisation;
    }

    public function setEtatCotisation(string $etat_cotisation): self
    {
        $this->etat_cotisation = $etat_cotisation;

        return $this;
    }
}