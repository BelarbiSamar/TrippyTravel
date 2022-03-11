<?php

namespace App\Entity;

use App\Repository\AttractionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;


/**
 * @ORM\Entity(repositoryClass=AttractionRepository::class)
 * @Vich\Uploadable
 */
class Attraction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\
     * NotBlank(message="Inserer un libelle svp")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\
     * Length(max=30)
     */
    private $localisation;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\
     * Length(max=30)
     */
    private $horraire;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieAttraction::class, inversedBy="attraction")
     */
    private $id_categorie;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="attraction_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\OneToMany(targetEntity=AttractionReservation::class, mappedBy="attraction", cascade={"persist", "remove"})
     */
    private $attractionReservations;

    public function __construct()
    {
        $this->attractionReservations = new ArrayCollection();
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

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getHorraire(): ?string
    {
        return $this->horraire;
    }

    public function setHorraire(?string $horraire): self
    {
        $this->horraire = $horraire;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getIdCategorie(): ?CategorieAttraction
    {
        return $this->id_categorie;
    }

    public function setIdCategorie(?CategorieAttraction $id_categorie): self
    {
        $this->id_categorie = $id_categorie;

        return $this;
    }
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return Collection<int, AttractionReservation>
     */
    public function getAttractionReservations(): Collection
    {
        return $this->attractionReservations;
    }

    public function addAttractionReservation(AttractionReservation $attractionReservation): self
    {
        if (!$this->attractionReservations->contains($attractionReservation)) {
            $this->attractionReservations[] = $attractionReservation;
            $attractionReservation->setAttraction($this);
        }

        return $this;
    }

    public function removeAttractionReservation(AttractionReservation $attractionReservation): self
    {
        if ($this->attractionReservations->removeElement($attractionReservation)) {
            // set the owning side to null (unless already changed)
            if ($attractionReservation->getAttraction() === $this) {
                $attractionReservation->setAttraction(null);
            }
        }

        return $this;
    }
}
