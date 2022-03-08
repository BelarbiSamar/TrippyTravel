<?php

namespace App\Entity;

use App\Repository\ChambreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ChambreRepository::class)
 */
class Chambre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message = "champs ne doit pas etre vide")
     * )
     */
    private $typechambre;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     *      message = "champs ne doit pas etre vide")
     * )
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message = "champs ne doit pas etre vide")
     * )
     */
    private $description_chambre;

    /**
     * @ORM\ManyToOne(targetEntity=Hotel::class, inversedBy="chambre")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hotel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypechambre(): ?string
    {
        return $this->typechambre;
    }

    public function setTypechambre(string $typechambre): self
    {
        $this->typechambre = $typechambre;

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

    public function getDescriptionChambre(): ?string
    {
        return $this->description_chambre;
    }

    public function setDescriptionChambre(string $description_chambre): self
    {
        $this->description_chambre = $description_chambre;

        return $this;
    }

    public function getHotel(): ?hotel
    {
        return $this->hotel;
    }

    public function setHotel(?hotel $hotel): self
    {
        $this->hotel = $hotel;

        return $this;
    }
}
