<?php

namespace App\Entity;

use App\Repository\SizeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: SizeRepository::class)]
#[Broadcast]
class Size
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $size = null;

    #[ORM\OneToMany(mappedBy: 'sizeId', targetEntity: GoodsSize::class)]
    private Collection $sizeGoods;

    public function __construct()
    {
        $this->sizeGoods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): static
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return Collection<int, GoodsSize>
     */
    public function getSizeGoods(): Collection
    {
        return $this->sizeGoods;
    }

    public function addSizeGood(GoodsSize $sizeGood): static
    {
        if (!$this->sizeGoods->contains($sizeGood)) {
            $this->sizeGoods->add($sizeGood);
            $sizeGood->setSizeId($this);
        }

        return $this;
    }

    public function removeSizeGood(GoodsSize $sizeGood): static
    {
        if ($this->sizeGoods->removeElement($sizeGood)) {
            // set the owning side to null (unless already changed)
            if ($sizeGood->getSizeId() === $this) {
                $sizeGood->setSizeId(null);
            }
        }

        return $this;
    }
}
