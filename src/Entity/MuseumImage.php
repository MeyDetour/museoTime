<?php

namespace App\Entity;

use App\Repository\MuseumImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: MuseumImageRepository::class)]
class MuseumImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'museumImage', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(["museums"])]
    private ?Image $image = null;

    #[ORM\Column(length: 255)]
    private ?string $museumId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(Image $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getMuseumId(): ?string
    {
        return $this->museumId;
    }

    public function setMuseumId(string $museumId): static
    {
        $this->museumId = $museumId;

        return $this;
    }
}
