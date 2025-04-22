<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\OrderStatus;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: OrderStatus::class)]
    private ?OrderStatus $status = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, CartItem>
     */
    #[ORM\OneToMany(targetEntity: CartItem::class, mappedBy: 'cartItemOrder', orphanRemoval: true, cascade: ['persist'])]
    private Collection $cartItem;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now', new \DateTimeZone('CET'));
        $this->cartItem = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?OrderStatus
    {
        return $this->status;
    }

    public function setStatus(OrderStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUuid(): ?Uuid
    {
        return Uuid::fromString($this->uuid);
    }

    public function setUuid(Uuid $uuid): static
    {
        $this->uuid = $uuid->toString();

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, CartItem>
     */
    public function getCartItem(): Collection
    {
        return $this->cartItem;
    }

    public function addCartItem(CartItem $cartItem): static
    {
        if (!$this->cartItem->contains($cartItem)) {
            $this->cartItem->add($cartItem);
            $cartItem->setCartItemOrder($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): static
    {
        if ($this->cartItem->removeElement($cartItem)) {
            // set the owning side to null (unless already changed)
            if ($cartItem->getCartItemOrder() === $this) {
                $cartItem->setCartItemOrder(null);
            }
        }

        return $this;
    }
}
