<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CreateOrderDto;
use App\Dto\UpdateOrderStatusDto;
use App\Entity\Order;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/orders/{id}', name: 'find_order', methods: ['GET'])]
    public function find(
        Order $order,
    ): JsonResponse {
        return $this->json(['order' => [
            'uuid' => $order->getUuid()->toString(),
            'status' => $order->getStatus()->value,
            'total' => $order->getTotal(),
        ]], Response::HTTP_OK);
    }

    #[Route('/orders', name: 'create_order', methods: ['POST'])]
    public function post(
        CreateOrderDto $createOrderDto,
        OrderService $orderService,
    ): JsonResponse {
        $orderService->createOrder($createOrderDto);

        return $this->json(['success' => true], Response::HTTP_OK);
    }

    #[Route('/orders/{id}', name: 'create_order', methods: ['PATCH'])]
    public function patch(
        Order $order,
        UpdateOrderStatusDto $updateOrderStatusDto,
        OrderService $orderService,
    ): JsonResponse {
        $orderService->updateOrderStatus($order, $updateOrderStatusDto);

        return $this->json(['success' => true], Response::HTTP_OK);
    }
}
