<?php

namespace App\Entity;

use App\Repository\ShareRepository;
use App\Service\MuseumsService;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ShareRepository::class)]
class Share
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["share"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["share"])]
    private ?string $museumId = null;


    #[ORM\Column]
    #[Groups(["share"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'sharesSender')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["share"])]
    private ?User $sender = null;

    #[ORM\ManyToOne(inversedBy: 'sharesReceiver')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["share"])]
    private ?User $receiver = null;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?string
    {
        if (!$this->createdAt) {
            return null;
        }

        return $this->createdAt->format('Y-m-d');
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): static
    {
        $this->receiver = $receiver;

        return $this;
    }
}
