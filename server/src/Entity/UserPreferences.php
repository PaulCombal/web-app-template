<?php

namespace App\Entity;

use App\Repository\UserPreferencesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserPreferencesRepository::class)
 */
class UserPreferences
{
    public function __construct($user = null) {
        $this->user = $user;
    }
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $accepted_cgu;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $subscribed_newsletter;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="userPreferences", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAcceptedCgu(): ?bool
    {
        return $this->accepted_cgu;
    }

    public function setAcceptedCgu(?bool $accepted_cgu): self
    {
        $this->accepted_cgu = $accepted_cgu;

        return $this;
    }

    public function getSubscribedNewsletter(): ?bool
    {
        return $this->subscribed_newsletter;
    }

    public function setSubscribedNewsletter(?bool $subscribed_newsletter): self
    {
        $this->subscribed_newsletter = $subscribed_newsletter;

        return $this;
    }

    public function getuser(): ?User
    {
        return $this->user;
    }

    public function setuser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
