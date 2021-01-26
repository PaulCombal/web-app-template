<?php

namespace App\Entity;

use App\Repository\UserLoginRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserLoginRepository::class)
 */
class UserLogin
{
    public function __construct($user = null, $op = null) {
        $this->date = new \DateTime();
        $this->user = $user;
        $this->openid_provider = $op;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $openid_provider;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="user_logins")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getOpenidProvider(): ?string
    {
        return $this->openid_provider;
    }

    public function setOpenidProvider(string $openid_provider): self
    {
        $this->openid_provider = $openid_provider;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
