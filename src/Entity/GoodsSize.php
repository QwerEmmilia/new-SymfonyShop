<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\GoodsSizeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: GoodsSizeRepository::class)]
#[Broadcast]
class GoodsSize
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'goodsSizes')]
    private ?Goods $goodsId = null;

    #[ORM\ManyToOne(inversedBy: 'sizeGoods')]
    private ?Size $sizeId;

    #[ORM\OneToMany(mappedBy: 'goodsSizeId', targetEntity: OrderDetails::class)]
    private Collection $orderDetails;

    #[ORM\Column]
    private ?int $quantity = null;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSizeId(): ?Size
    {
        return $this->sizeId;
    }

    public function setSizeId(?Size $sizeId): static
    {
        $this->sizeId = $sizeId;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return Collection<int, OrderDetails>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetails $orderDetail): static
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails->add($orderDetail);
            $orderDetail->setGoodsSizeId($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetails $orderDetail): static
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getGoodsSizeId() === $this) {
                $orderDetail->setGoodsSizeId(null);
            }
        }

        return $this;
    }

}
