<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Service\ImageService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["profile","share"])]
    private ?int $id = null;

//    #[ORM\Column(length: 180)]
//    #[Groups(["profile","users","notifications"])]
//    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;



    #[ORM\Column(length: 255)]
    #[Groups(["profile","share"])]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    #[Groups(["profile"])]
    private ?string $localisation = null;

    #[ORM\OneToOne(inversedBy: 'userImage', cascade: ['persist', 'remove'])]
    #[Groups(["profile"])]
    private ?Image $profileImage = null;

    /**
     * @var Collection<int, FavoriteList>
     */
    #[ORM\OneToMany(targetEntity: FavoriteList::class, mappedBy: 'createdBy', orphanRemoval: true)]

    private Collection $favoriteLists;

    /**
     * @var Collection<int, Share>
     */
    #[ORM\OneToMany(targetEntity: Share::class, mappedBy: 'sender')]
    private Collection $sharesSender;

    /**
     * @var Collection<int, Share>
     */
    #[ORM\OneToMany(targetEntity: Share::class, mappedBy: 'receiver')]
    private Collection $sharesReceiver;



    public function __construct()
    {
        $this->favoriteLists = new ArrayCollection();
        $this->sharesSender = new ArrayCollection();
        $this->sharesReceiver = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

//    public function getEmail(): ?string
//    {
//        return $this->email;
//    }
//
//    public function setEmail(string $email): static
//    {
//        $this->email = $email;
//
//        return $this;
//    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getProfileImage(): ?Image
    {
        return $this->profileImage;
    }

    public function setProfileImage(?Image $profileImage): static
    {
        $this->profileImage = $profileImage;

        return $this;
    }

    /**
     * @return Collection<int, FavoriteList>
     */
    public function getFavoriteLists(): Collection
    {
        return $this->favoriteLists;
    }

    public function addFavoriteList(FavoriteList $favoriteList): static
    {
        if (!$this->favoriteLists->contains($favoriteList)) {
            $this->favoriteLists->add($favoriteList);
            $favoriteList->setCreatedBy($this);
        }

        return $this;
    }

    public function removeFavoriteList(FavoriteList $favoriteList): static
    {
        if ($this->favoriteLists->removeElement($favoriteList)) {
            // set the owning side to null (unless already changed)
            if ($favoriteList->getCreatedBy() === $this) {
                $favoriteList->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Share>
     */
    public function getSharesSender(): Collection
    {
        return $this->sharesSender;
    }

    public function addSharesSender(Share $sharesSender): static
    {
        if (!$this->sharesSender->contains($sharesSender)) {
            $this->sharesSender->add($sharesSender);
            $sharesSender->setSender($this);
        }

        return $this;
    }

    public function removeSharesSender(Share $sharesSender): static
    {
        if ($this->sharesSender->removeElement($sharesSender)) {
            // set the owning side to null (unless already changed)
            if ($sharesSender->getSender() === $this) {
                $sharesSender->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Share>
     */
    public function getSharesReceiver(): Collection
    {
        return $this->sharesReceiver;
    }

    public function addSharesReceiver(Share $sharesReceiver): static
    {
        if (!$this->sharesReceiver->contains($sharesReceiver)) {
            $this->sharesReceiver->add($sharesReceiver);
            $sharesReceiver->setReceiver($this);
        }

        return $this;
    }

    public function removeSharesReceiver(Share $sharesReceiver): static
    {
        if ($this->sharesReceiver->removeElement($sharesReceiver)) {
            // set the owning side to null (unless already changed)
            if ($sharesReceiver->getReceiver() === $this) {
                $sharesReceiver->setReceiver(null);
            }
        }

        return $this;
    }




}
