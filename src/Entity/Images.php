<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagesRepository::class)]
class Images
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'goodsImages')]
    private ?Goods $goodsId = null;

    #[ORM\Column(length: 255)]
    private ?string $images = null;

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

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(string $images): static
    {
        $this->images = $images;

        return $this;
    }
}
