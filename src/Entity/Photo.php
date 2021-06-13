<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PhotoRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PhotoRepository::class)
 */

#[ApiResource(
    normalizationContext: ['groups' => ['objet']],
    // denormalizationContext: ['groups' => ['write']],
)]
class Photo
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
    #[Groups(['objet'])]

    private $lien;

    /**
     * @ORM\ManyToOne(targetEntity=Objet::class, inversedBy="photos")
     */

    private $objet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

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
}