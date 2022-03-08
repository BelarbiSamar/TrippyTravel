<?php

namespace App\Entity;

use App\Repository\HotelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=HotelRepository::class)
 * @Vich\Uploadable()
 */
class Hotel
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
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $localisation;

    /**
     * @ORM\Column(type="integer")
     * * @Assert\Length(
     *      min = 1,
     *      max = 5,
     *      minMessage = "Your number of stars must be at least {{ limit }} characters long",
     *      maxMessage = "Your number of stars cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private $nbetoile;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbchdispo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description_hotel;

    /**
     * @ORM\OneToMany(targetEntity=Chambre::class, mappedBy="hotel", orphanRemoval=true,cascade={"all"}, orphanRemoval=true)
     */
    private $chambre;

    /**
     * @ORM\OneToMany(targetEntity=Hotelimage::class, mappedBy="hotel", cascade={"persist", "remove"})
     */
    private $hotelimage;

    public function __toString()
{
    return $this->getLibelle();
}

    public function __construct()
    {
        $this->chambre = new ArrayCollection();
        $this->hotelimage = new ArrayCollection();
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

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getNbetoile(): ?int
    {
        return $this->nbetoile;
    }

    public function setNbetoile(int $nbetoile): self
    {
        $this->nbetoile = $nbetoile;

        return $this;
    }

    public function getNbchdispo(): ?int
    {
        return $this->nbchdispo;
    }

    public function setNbchdispo(int $nbchdispo): self
    {
        $this->nbchdispo = $nbchdispo;

        return $this;
    }

    public function getDescriptionHotel(): ?string
    {
        return $this->description_hotel;
    }

    public function setDescriptionHotel(string $description_hotel): self
    {
        $this->description_hotel = $description_hotel;

        return $this;
    }

    /**
     * @return Collection|Chambre[]
     */
    public function getChambre(): Collection
    {
        return $this->chambre;
    }

    public function addChambre(Chambre $chambre): self
    {
        if (!$this->chambre->contains($chambre)) {
            $this->chambre[] = $chambre;
            $chambre->setHotel($this);
        }

        return $this;
    }

    public function removeChambre(Chambre $chambre): self
    {
        if ($this->chambre->removeElement($chambre)) {
            // set the owning side to null (unless already changed)
            if ($chambre->getHotel() === $this) {
                $chambre->setHotel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Hotelimage[]
     */
    public function getHotelimage(): Collection
    {
        return $this->hotelimage;
    }

    public function addHotelimage(Hotelimage $hotelimage): self
    {
        if (!$this->hotelimage->contains($hotelimage)) {
            $this->hotelimage[] = $hotelimage;
            $hotelimage->setHotel($this);
        }

        return $this;
    }

    public function removeHotelimage(Hotelimage $hotelimage): self
    {
        if ($this->hotelimage->removeElement($hotelimage)) {
            // set the owning side to null (unless already changed)
            if ($hotelimage->getHotel() === $this) {
                $hotelimage->setHotel(null);
            }
        }

        return $this;
    }

}
