<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CreateOrderDto;
use App\Entity\CartItem;
use App\Entity\Order;
use App\Enum\OrderStatus;
use App\Repository\OrderRepository;
use Symfony\Component\Uid\Uuid;

class OrderService
{
    public function __construct(private readonly OrderRepository $orderRepository)
    {
    }

    public function createOrder(CreateOrderDto $dto): void
    {
        $order = new Order();

        /** @var \App\Dto\CartItemDto $cartItemDto */
        foreach ($dto->items as $key => $cartItemDto) {
            $cartItem = new CartItem();
  
            $cartItem->setPrice($cartItemDto->price);
            $cartItem->setProductId($cartItemDto->productId);
            $cartItem->setProductName($cartItemDto->productName);
            $cartItem->setQuantity($cartItemDto->quantity);


            $order->addCartItem($cartItem);
        }

        $order->setUuid(Uuid::v4());
        $order->setStatus(OrderStatus::NEW);

        $this->orderRepository->save($order);
    }
}
