<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmpruntRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EmpruntRepository::class)
 *  @ORM\HasLifecycleCallbacks
 */
class Emprunt
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_reservation;

    /**
     * @ORM\Column(type="date")
     */
    #[Assert\Type("\DateTimeInterface", message:"Veuillez entrer une date de début valide pour l'emprunt")]
    #[Assert\NotBlank(message:"Veuillez entrer une date de début d'emprunt")]
    #[Assert\GreaterThan('yesterday', message:"Veuillez entrer une date de début d'emprunt correcte")]
    private $date_debut;

    /**
     * @ORM\Column(type="date")
     */

    #[Assert\Type("\DateTimeInterface", message:"Veuillez entrer une date de fin valide pour l'emprunt au format 12/12/2021")]
    #[Assert\NotBlank(message:"Veuillez entrer une date de fin d'emprunt")]
    #[Assert\GreaterThan('yesterday', message:"Veuillez entrer une date de fin d'emprunt correcte")]
    private $date_fin;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remarque;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    #[Assert\Type("\DateTimeInterface", message:"Veuillez entrer une date de retour de l'objet valide au format 12/12/2021")]
    private $date_retour_objet;

    /**
     * @ORM\Column(type="integer")
     */
    private $depot_rajoute;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $penalites;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $prix_emprunt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Objet::class, inversedBy="emprunts")
     * @ORM\JoinColumn(nullable=false)
     */
    // #[Assert\NotNull(message:"Veuillez choisir un objet à emprunter")]

    private $objet;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="emprunts")
     */

    private $adherent;

    /**
     * @ORM\ManyToOne(targetEntity=SuperAdmin::class, inversedBy="emprunts")
     */
    private $super_admin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\Column(type="boolean")
     */

    #[Assert\NotNull(message:"Veuillez choisir si l'emprunt est réglé ce jour ou non")]

    private $emprunt_regle;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $penalites_payees;

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
                $this->getObjet()->getDenomination() .
                    time() .
                    hash('sha1', $this->getObjet()->getDenomination())
            );
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->date_reservation;
    }

    public function setDateReservation(
        ?\DateTimeInterface $date_reservation
    ): self {
        $this->date_reservation = $date_reservation;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(?string $remarque): self
    {
        $this->remarque = $remarque;

        return $this;
    }

    public function getDateRetourObjet(): ?\DateTimeInterface
    {
        return $this->date_retour_objet;
    }

    public function setDateRetourObjet(
        ?\DateTimeInterface $date_retour_objet
    ): self {
        $this->date_retour_objet = $date_retour_objet;

        return $this;
    }

    public function getDepotRajoute(): ?int
    {
        return $this->depot_rajoute;
    }

    public function setDepotRajoute(int $depot_rajoute): self
    {
        $this->depot_rajoute = $depot_rajoute;

        return $this;
    }

    public function getPenalites(): ?string
    {
        return $this->penalites;
    }

    public function setPenalites(?string $penalites): self
    {
        $this->penalites = $penalites;

        return $this;
    }

    public function getPrixEmprunt(): ?string
    {
        return $this->prix_emprunt;
    }

    public function setPrixEmprunt(string $prix_emprunt): self
    {
        $this->prix_emprunt = $prix_emprunt;

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

    public function getObjet(): ?Objet
    {
        return $this->objet;
    }

    public function setObjet(?Objet $objet): self
    {
        $this->objet = $objet;

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

    public function getSuperAdmin(): ?SuperAdmin
    {
        return $this->super_admin;
    }

    public function setSuperAdmin(?SuperAdmin $super_admin): self
    {
        $this->super_admin = $super_admin;

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

    public function getEmpruntRegle(): ?bool
    {
        return $this->emprunt_regle;
    }

    public function setEmpruntRegle(bool $emprunt_regle): self
    {
        $this->emprunt_regle = $emprunt_regle;

        return $this;
    }

    public function getPenalitesPayees(): ?bool
    {
        return $this->penalites_payees;
    }

    public function setPenalitesPayees(?bool $penalites_payees): self
    {
        $this->penalites_payees = $penalites_payees;

        return $this;
    }
}
