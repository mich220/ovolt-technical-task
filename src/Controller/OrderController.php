<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CreateOrderDto;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/orders', name: 'create_order', methods: ['GET', 'POST'])]
    public function post(
        CreateOrderDto $createOrderDto, 
        OrderService $orderService
    ): JsonResponse
    {
        $orderService->createOrder($createOrderDto);        

        return $this->json(['success' => true], Response::HTTP_OK);
    }
}
