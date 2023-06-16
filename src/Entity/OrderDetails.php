<?php

namespace App\Entity;

use App\Repository\OrderDetailsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: OrderDetailsRepository::class)]
#[Broadcast]
class OrderDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'OrderDetails')]
    private ?Order $orderId = null;

    #[ORM\ManyToOne(inversedBy: 'orderDetails')]
    private ?Goods $goodsId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): ?Order
    {
        return $this->orderId;
    }

    public function setOrderId(?Order $orderId): static
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getGoodsId(): ?Goods
    {
        return $this->goodsId;
    }

    public function setGoodsId(?Goods $goodsId): static
    {
        $this->goodsId = $goodsId;

        return $this;
    }
}
