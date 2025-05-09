<?php

namespace App\Entity;

use App\Repository\AddProductHistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\Timestampable;

#[ORM\Entity(repositoryClass: AddProductHistoryRepository::class)]
class AddProductHistory
{

    use Timestampable;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'addProductHistories')]
    private ?Products $product = null;

    #[ORM\Column]
    private ?int $quantity = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(?Products $product): static
    {
        $this->product = $product;

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
}