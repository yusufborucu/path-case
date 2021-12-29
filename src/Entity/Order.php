<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orders")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $orderCode;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="orders")
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $address;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $shippingDate;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOrderCode(): ?string
    {
        return $this->orderCode;
    }

    public function setOrderCode(string $orderCode): self
    {
        $this->orderCode = $orderCode;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getShippingDate(): ?object
    {
        return $this->shippingDate;
    }

    public function setShippingDate(object $shippingDate): self
    {
        $this->shippingDate = $shippingDate;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'orderCode' => $this->getOrderCode(),
            'productId' => $this->getProduct()->getId(),
            'product' => $this->getProduct()->getName(),
            'quantity' => $this->getQuantity(),
            'address' => $this->getAddress(),
            'shippingDate' => $this->getShippingDate()
        ];
    }

}
