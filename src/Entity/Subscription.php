<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
#[Broadcast]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $isMaleSubscriber = null;

    #[ORM\Column]
    private ?bool $isFemaleSubscriber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isIsMaleSubscriber(): ?bool
    {
        return $this->isMaleSubscriber;
    }

    public function setIsMaleSubscriber(bool $isMaleSubscriber): static
    {
        $this->isMaleSubscriber = $isMaleSubscriber;

        return $this;
    }

    public function isIsFemaleSubscriber(): ?bool
    {
        return $this->isFemaleSubscriber;
    }

    public function setIsFemaleSubscriber(bool $isFemaleSubscriber): static
    {
        $this->isFemaleSubscriber = $isFemaleSubscriber;

        return $this;
    }
}
