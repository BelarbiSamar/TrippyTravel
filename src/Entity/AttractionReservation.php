<?php

namespace App\Entity;

use App\Repository\AttractionReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;


/**
 * @ORM\Entity(repositoryClass=AttractionReservationRepository::class)
 */
class AttractionReservation
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
    private $prix;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $start;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity=Attraction::class, inversedBy="attractionReservations")
     */
    private $attraction;

    public function __toString()
    {
        return $this->getPrix();
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(?\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(?\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getAttraction(): ?Attraction
    {
        return $this->attraction;
    }

    public function setAttraction(?Attraction $attraction): self
    {
        $this->attraction = $attraction;

        return $this;
    }
}
