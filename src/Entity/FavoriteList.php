<?php

namespace App\Entity;

use App\Repository\FavoriteListRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: FavoriteListRepository::class)]
class FavoriteList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["favoriteList"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["favoriteList"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["favoriteList", "profile"])]
    private ?string $idsOfMuseums = null;

    #[ORM\ManyToOne(inversedBy: 'favoriteLists')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["favoriteList"])]
    private ?User $createdBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getIdsOfMuseums(): ?string
    {
        return $this->idsOfMuseums;
    }

    public function setIdsOfMuseums(?string $idsOfMuseums): static
    {
        $this->idsOfMuseums = $idsOfMuseums;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}
