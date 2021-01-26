<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="ascii_string", nullable=true, unique=true)
     */
    private $google_sub;

    /**
     * @ORM\Column(type="ascii_string", nullable=true, unique=true)
     */
    private $apple_sub;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $given_name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $family_name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $picture;

    // logins
    // preferences

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=UserLogin::class, mappedBy="user", orphanRemoval=true)
     */
    private $user_logins;

    /**
     * @ORM\OneToOne(targetEntity=UserPreferences::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $user_preferences;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="user", orphanRemoval=true)
     */
    private $addresses;

    public function __construct()
    {
        $this->user_logins = new ArrayCollection();
        $this->addresses = new ArrayCollection();
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
        return $this->google_sub;
    }

    /**
     * @param mixed $google_sub
     * @return User
     */
    public function setGoogleSub($google_sub)
    {
        $this->google_sub = $google_sub;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAppleSub()
    {
        return $this->apple_sub;
    }

    /**
     * @param mixed $apple_sub
     * @return User
     */
    public function setAppleSub($apple_sub)
    {
        $this->apple_sub = $apple_sub;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGivenName()
    {
        return $this->given_name;
    }

    /**
     * @param mixed $given_name
     * @return User
     */
    public function setGivenName($given_name)
    {
        $this->given_name = $given_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->full_name;
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
        return $this->family_name;
    }

    /**
     * @param mixed $family_name
     * @return User
     */
    public function setFamilyName($family_name)
    {
        $this->family_name = $family_name;
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
        return $this->user_logins;
    }

    public function addUserLogin(UserLogin $userLogin): self
    {
        if (!$this->user_logins->contains($userLogin)) {
            $this->user_logins[] = $userLogin;
            $userLogin->setUser($this);
        }

        return $this;
    }

    public function removeUserLogin(UserLogin $userLogin): self
    {
        if ($this->user_logins->removeElement($userLogin)) {
            // set the owning side to null (unless already changed)
            if ($userLogin->getUser() === $this) {
                $userLogin->setUser(null);
            }
        }

        return $this;
    }

    public function getUserPreferences(): ?UserPreferences
    {
        return $this->user_preferences;
    }

    public function setUserPreferences(UserPreferences $user_preferences): self
    {
        // set the owning side of the relation if necessary
        if ($user_preferences->getXxxuser() !== $this) {
            $user_preferences->setXxxuser($this);
        }

        $this->user_preferences = $user_preferences;

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

}
