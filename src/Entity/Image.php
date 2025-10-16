<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[Vich\Uploadable]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    // ... other fields
    public function getId(): ?int
    {
        return $this->id;
    }

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'museums', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    // NOTE: This field and the next one need to be nullable, otherwise the deletion won't work
    //       if you want non-nullable fields, set the "erase_fields" option to false in the mapping config
    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(mappedBy: 'image', cascade: ['persist', 'remove'])]
    private ?MuseumImage $museumImage = null;

    #[ORM\OneToOne(mappedBy: 'profileImage', cascade: ['persist', 'remove'])]
    private ?User $userImage = null;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
    public function getUpdatedAt(){
        return $this->updatedAt;
    }
    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function getMuseumImage(): ?MuseumImage
    {
        return $this->museumImage;
    }

    public function setMuseumImage(MuseumImage $museumImage): static
    {
        // set the owning side of the relation if necessary
        if ($museumImage->getImage() !== $this) {
            $museumImage->setImage($this);
        }

        $this->museumImage = $museumImage;

        return $this;
    }

    public function getUserImage(): ?User
    {
        return $this->userImage;
    }

    public function setUserImage(?User $userImage): static
    {
        // unset the owning side of the relation if necessary
        if ($userImage === null && $this->userImage !== null) {
            $this->userImage->setProfileImage(null);
        }

        // set the owning side of the relation if necessary
        if ($userImage !== null && $userImage->getProfileImage() !== $this) {
            $userImage->setProfileImage($this);
        }

        $this->userImage = $userImage;

        return $this;
    }
}
