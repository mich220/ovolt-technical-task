<?php

declare(strict_types=1);

namespace App\EntityListener\Doctrine;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;

class OrderTotalListener implements EventSubscriber
{
    public function __construct(private OrderRepository $orderRepository)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [Events::postLoad];
    }

    public function postLoad(Order $order): void
    {
        $order->setTotal(
            $this->orderRepository->findTotalForOrder($order->getId())
        );
    }
}
