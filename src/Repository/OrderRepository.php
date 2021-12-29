<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Order::class);
        $this->manager = $manager;
    }

    public function addOrder($user, $orderCode, $product, $quantity, $address)
    {
        $order = new Order();

        $order
            ->setUser($user)
            ->setOrderCode($orderCode)
            ->setProduct($product)
            ->setQuantity($quantity)
            ->setAddress($address);

        $this->manager->persist($order);
        $this->manager->flush();

        return $order;
    }

    public function updateOrder(Order $order): Order
    {
        $this->manager->persist($order);
        $this->manager->flush();

        return $order;
    }

    public function removeOrder(Order $order)
    {
        $this->manager->remove($order);
        $this->manager->flush();
    }
}
