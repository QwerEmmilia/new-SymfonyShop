<?php

namespace App\Entity;

use App\Repository\GoodsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Gedmo\Mapping\Annotation\Slug;

#[ORM\Entity(repositoryClass: GoodsRepository::class)]
#[Broadcast]
class Goods
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Slug(fields: ['name'])]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'goodsId', targetEntity: OrderDetails::class)]
    private Collection $orderDetails;

    #[ORM\Column(length: 255)]
    private ?string $composition = null;

    #[ORM\OneToMany(mappedBy: 'goodsId', targetEntity: GoodsSize::class)]
    private Collection $goodsSizes;

    #[ORM\Column(length: 50)]
    private ?string $Gender = null;

    #[ORM\Column(length: 100)]
    private ?string $Type = null;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
        $this->goodsSizes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

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
            $orderDetail->setGoodsId($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetails $orderDetail): static
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getGoodsId() === $this) {
                $orderDetail->setGoodsId(null);
            }
        }

        return $this;
    }

    public function getComposition(): ?string
    {
        return $this->composition;
    }

    public function setComposition(string $composition): static
    {
        $this->composition = $composition;

        return $this;
    }

    /**
     * @return Collection<int, GoodsSize>
     */
    public function getGoodsSizes(): Collection
    {
        return $this->goodsSizes;
    }

    public function addGoodsSize(GoodsSize $goodsSize): static
    {
        if (!$this->goodsSizes->contains($goodsSize)) {
            $this->goodsSizes->add($goodsSize);
            $goodsSize->setGoodsId($this);
        }

        return $this;
    }

    public function removeGoodsSize(GoodsSize $goodsSize): static
    {
        if ($this->goodsSizes->removeElement($goodsSize)) {
            // set the owning side to null (unless already changed)
            if ($goodsSize->getGoodsId() === $this) {
                $goodsSize->setGoodsId(null);
            }
        }

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->Gender;
    }

    public function setGender(string $Gender): static
    {
        $this->Gender = $Gender;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): static
    {
        $this->Type = $Type;

        return $this;
    }
}

