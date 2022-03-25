<?php

namespace App\Entity;

use App\Repository\ExcursionimageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;



/**
 * @ORM\Entity(repositoryClass=ExcursionimageRepository::class)
 * @Vich\Uploadable
 */
class Excursionimage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("excursion")
     */
    private $id;

    /**
     * @Vich\UploadableField(mapping="excursion_images", fileNameProperty="imageName")
     * @var File
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"image/jpg", "image/jpeg", "image/gif", "image/png"},
     *     mimeTypesMessage = "Veuillez télécharger une image valide"
     * )
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string")
     * @var string|null
     * @Groups("excursion")
     */
    private $imageName;

    /**
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     * @var \DateTime
     * @Groups("excursion")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Excursion::class, inversedBy="excursionimages")
     */
    private $excursion;

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return Excursionimage
     */
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
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageName(?string $name): void
    {
        $this->imageName = $name;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getExcursion(): ?Excursion
    {
        return $this->excursion;
    }

    public function setExcursion(?Excursion $excursion): self
    {
        $this->excursion = $excursion;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
