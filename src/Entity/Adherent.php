<?php

namespace App\Entity;

use DateInterval;
use App\Entity\Objet;
use App\Entity\Emprunt;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\AdhesionBibliotheque;
use App\Repository\AdherentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AdherentRepository::class)
 * @ORM\HasLifecycleCallbacks
 */

#[UniqueEntity('email', message: "L'adresse email existe déjà dans la base de données")]

class Adherent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *  
     */
    
#[Groups(['person'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    #[Groups(['person'])]

    #[Assert\NotBlank(message:"Veuillez entrer un nom")]
    #[Assert\Length(
        min: 2,
        max: 30,
        minMessage: 'Le nom doit faire plus de {{ limit }} caractères',
        maxMessage: 'Le nom doit faire {{ limit }} caractères maximum',
    )]
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
#[Groups(['person'])]
    
     #[Assert\NotBlank(message:"Veuillez entrer un prénom")]
     #[Assert\Length(
        min: 2,
        max: 30,
        minMessage: 'Le prénom doit faire plus de {{ limit }} caractères',
        maxMessage: 'Le prénom doit faire {{ limit }} caractères maximum',
    )]
    private $prenom;


    private $nomprenom;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */

    #[Assert\NotBlank(message:"Veuillez entrer une adresse")]
    #[Assert\Length(
        min: 5,
        minMessage: 'L\'adresse doit faire plus de {{ limit }} caractères',
    )]
#[Groups(['person'])]

    private $adresse;

    /**
     * @ORM\Column(type="string", length=6)
     */

    #[Assert\NotBlank(message:"Veuillez entrer un code postal")]
#[Groups(['person'])]
    
    private $cp;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    #[Assert\NotBlank(message:"Veuillez entrer une ville")]
    #[Assert\Length(
        min: 3,
        minMessage: 'La ville doit faire plus de {{ limit }} caractères',
    )]
#[Groups(['person'])]

    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Assert\Email(
        message: 'Veuillez entrer un email valide',
    )]
#[Groups(['person'])]

    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    #[Assert\NotBlank(message:"Veuillez entrer un numéro de téléphone")]
    #[Assert\Positive(message:"Veuillez entrer un numéro de téléphone valide ")]
    #[Assert\Length( min: 10,
    max:10,
    exactMessage:"Veuillez entrer un numéro de téléphone valide (10 chiffres sans espace)")]
#[Groups(['person'])]
    
    
    private $telephone;

    /**
     * @ORM\Column(type="date")
     * 
     */
    #[Groups(['person'])]

    #[Assert\Type("\DateTimeInterface", message:"Veuillez entrer une date de naissance valide")]
    #[Assert\NotBlank(message:"Veuillez entrer une date de naissance")]
  
    private $date_naissance;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    #[Groups(['person'])]

    #[Assert\NotBlank(message:"Veuillez entrer un lieu de naissance")]
    #[Assert\Length(
        min: 3,
        minMessage: 'Le lieu de naissance doit faire plus de {{ limit }} caractères',
    )]
    private $lieu_naissance;


    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
#[Groups(['person'])]

    #[Assert\PositiveOrZero(message:"Veuillez entrer un montant de cotisation valide ou 0")]
    private $montant_cotisation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
#[Groups(['person'])]

    private $moyen_paiement;

    /**
     * @ORM\Column(type="date")
     */
#[Groups(['person'])]

    private $date_adhesion;

    /**
     * @ORM\Column(type="boolean")
     */
#[Groups(['person'])]

    private $compte_actif;

    /**
     * @ORM\OneToOne(targetEntity=AdhesionBibliotheque::class, mappedBy="adherent", cascade={"persist", "remove"})
     */

    #[Groups(['person'])]
    
    private $adhesionBibliotheque;

    /**
     * @ORM\OneToMany(targetEntity=Objet::class, mappedBy="adherent", cascade={"persist"})
     */

    private $objets;

    /**
     * @ORM\OneToMany(targetEntity=Emprunt::class, mappedBy="adherent", orphanRemoval=true)
     */
    #[Groups(['person'])]

    private $emprunts;

    /**
     * @ORM\Column(type="string", length=255)
     */
#[Groups(['person'])]
    
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['person'])]

    #[Assert\NotNull(message:"Veuillez indiquer un état pour la cotisation")]
    
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

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNomprenom(): ?string
    {
        return $this->nom.' '.$this->prenom;
    }

    public function setNomprenom(?string $nomprenom): self
    {
        $this->nomprenom = $nomprenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(?string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
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

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(?\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getLieuNaissance(): ?string
    {
        return $this->lieu_naissance;
    }

    public function setLieuNaissance(?string $lieu_naissance): self
    {
        $this->lieu_naissance = $lieu_naissance;

        return $this;
    }


    public function getMontantCotisation(): ?int
    {
        return $this->montant_cotisation;
    }

    public function setMontantCotisation(?int $montant_cotisation): self
    {
        $this->montant_cotisation = $montant_cotisation;

        return $this;
    }

    public function getMoyenPaiement(): ?string
    {
        return $this->moyen_paiement;
    }

    public function setMoyenPaiement(?string $moyen_paiement): self
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

    public function setEtatCotisation(?string $etat_cotisation): self
    {
        $this->etat_cotisation = $etat_cotisation;

        return $this;
    }
}