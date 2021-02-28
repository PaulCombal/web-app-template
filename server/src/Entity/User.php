<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @Groups({"basic", "admin"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("admin")
     * @ORM\Column(type="ascii_string", nullable=true, unique=true)
     */
    private $googleSub;

    /**
     * @Groups("admin")
     * @ORM\Column(type="ascii_string", nullable=true, unique=true)
     */
    private $appleSub;

    /**
     * @Groups({"private", "admin"})
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @Groups({"private", "admin"})
     * @ORM\Column(type="string", nullable=true)
     */
    private $givenName;

    /**
     * @Groups({"private", "admin"})
     * @ORM\Column(type="string", nullable=true)
     */
    private $familyName;

    /**
     * @Groups({"private", "admin"})
     * @ORM\Column(type="string", nullable=true)
     */
    private $fullName;

    /**
     * @Groups({"private", "admin"})
     * @ORM\Column(type="string", nullable=true)
     */
    private $picture;

    // logins
    // preferences

    /**
     * @Groups("admin")
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @Ignore()
     * @ORM\OneToMany(targetEntity=UserLogin::class, mappedBy="user", orphanRemoval=true)
     */
    private $userLogins;

    /**
     * @Ignore()
     * @ORM\OneToOne(targetEntity=UserPreferences::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $userPreferences;

    /**
     * @Ignore()
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="user", orphanRemoval=true)
     */
    private $addresses;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->userLogins = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return mixed
     */
    public function getGoogleSub()
    {
        return $this->googleSub;
    }

    /**
     * @param mixed $googleSub
     * @return User
     */
    public function setGoogleSub($googleSub)
    {
        $this->googleSub = $googleSub;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAppleSub()
    {
        return $this->appleSub;
    }

    /**
     * @param mixed $appleSub
     * @return User
     */
    public function setAppleSub($appleSub)
    {
        $this->appleSub = $appleSub;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGivenName()
    {
        return $this->givenName;
    }

    /**
     * @param mixed $givenName
     * @return User
     */
    public function setGivenName($givenName)
    {
        $this->givenName = $givenName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param mixed $full_name
     * @return User
     */
    public function setFullName($full_name)
    {
        $this->full_name = $full_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * @param mixed $familyName
     * @return User
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     * @return User
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
        return $this;
    }

    /**
     * @return Collection|UserLogin[]
     */
    public function getUserLogins(): Collection
    {
        return $this->userLogins;
    }

    public function addUserLogin(UserLogin $userLogin): self
    {
        if (!$this->userLogins->contains($userLogin)) {
            $this->userLogins[] = $userLogin;
            $userLogin->setUser($this);
        }

        return $this;
    }

    public function removeUserLogin(UserLogin $userLogin): self
    {
        if ($this->userLogins->removeElement($userLogin)) {
            // set the owning side to null (unless already changed)
            if ($userLogin->getUser() === $this) {
                $userLogin->setUser(null);
            }
        }

        return $this;
    }

    public function getUserPreferences(): ?UserPreferences
    {
        return $this->userPreferences;
    }

    public function setUserPreferences(UserPreferences $userPreferences): self
    {
        // set the owning side of the relation if necessary
        if ($userPreferences->getXxxuser() !== $this) {
            $userPreferences->setXxxuser($this);
        }

        $this->userPreferences = $userPreferences;

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setXxxuser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getXxxuser() === $this) {
                $address->setXxxuser(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}
