<?php

namespace App\Entity;

use App\Repository\CategorieAttractionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategorieAttractionRepository::class)
 */
class CategorieAttraction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\
     * NotBlank(message="Inserer un libelle svp")
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $contrainteAge;

    /**
     * @ORM\OneToMany(targetEntity=Attraction::class, mappedBy="id_categorie", cascade={"persist", "remove"})
     */
    private $attractions;

    public function __toString()
    {
        return $this->getLibelle();
    }

    public function __construct()
    {
        $this->attractions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getContrainteAge(): ?bool
    {
        return $this->contrainteAge;
    }

    public function setContrainteAge(bool $contrainteAge): self
    {
        $this->contrainteAge = $contrainteAge;

        return $this;
    }

    /**
     * @return Collection|Attraction[]
     */
    public function getAttractions(): Collection
    {
        return $this->attractions;
    }

    public function addAttraction(Attraction $attraction): self
    {
        if (!$this->attractions->contains($attraction)) {
            $this->attractions[] = $attraction;
            $attraction->setIdCategorie($this);
        }

        return $this;
    }

    public function removeAttraction(Attraction $attraction): self
    {
        if ($this->attractions->removeElement($attraction)) {
            // set the owning side to null (unless already changed)
            if ($attraction->getIdCategorie() === $this) {
                $attraction->setIdCategorie(null);
            }
        }

        return $this;
    }
}
