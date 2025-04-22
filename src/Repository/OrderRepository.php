<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function save(Order $order): void
    {
        $em = $this->getEntityManager();
        $em->persist($order);
        $em->flush();
    }

    public function findTotalForOrder(int $orderId): int
    {
        return (int) $this->createQueryBuilder('o')
            ->select('SUM(ci.price * ci.quantity) AS total')
            ->join('o.cartItem', 'ci')
            ->where('o.id = :id')
            ->setParameter('id', $orderId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
