<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\HelperService;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class OrderController
 * @package App\Controller
 *
 * @Route(path="/api/order")
 */
class OrderController extends AbstractController
{
    private $orderRepository;
    private $productRepository;
    private $helperService;

    public function __construct(HelperService $helperService, OrderRepository $orderRepository, ProductRepository $productRepository)
    {
        $this->helperService = $helperService;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/add", name="add_order", methods={"POST"})
     */
    public function add(Request $request, UserInterface $user): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $orderCode = $this->helperService->generateRandomString();
        $productId = $data['productId'];
        $quantity = $data['quantity'];
        $address = $data['address'];

        if (empty($productId) || empty($quantity) || empty($address)) {
            return new JsonResponse(['status' => 'productId, quantity and address cannot be empty.'], Response::HTTP_NO_CONTENT);
        }

        $product = $this->productRepository->findOneBy(['id' => $productId]);
        $addedOrder = $this->orderRepository->addOrder($user, $orderCode, $product, $quantity, $address);

        return new JsonResponse([
            'status' => 'Order successfully added.',
            'order' => $addedOrder->toArray()
        ], Response::HTTP_CREATED);
    }

    /**
     * @Route("/get/{id}", name="get_order", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $order = $this->orderRepository->findOneBy(['id' => $id]);

        if (empty($order)) {
            return new JsonResponse(['status' => 'Order not found.'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $order->getId(),
            'orderCode' => $order->getOrderCode(),
            'quantity' => $order->getQuantity(),
            'address' => $order->getAddress(),
            'shippingDate' => $order->getShippingDate(),
            'product' => $order->getProduct()->getName()
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/all", name="get_all_orders", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $orders = $this->orderRepository->findAll();
        $data = [];

        foreach ($orders as $order) {
            $data[] = [
                'id' => $order->getId(),
                'orderCode' => $order->getOrderCode(),
                'quantity' => $order->getQuantity(),
                'address' => $order->getAddress(),
                'shippingDate' => $order->getShippingDate(),
                'product' => $order->getProduct()->getName()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/update/{id}", name="update_order", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $now = new \DateTime();

        $order = $this->orderRepository->findOneBy(['id' => $id]);
        
        if ($now <= $order->getShippingDate()) {
            return new JsonResponse('Order cannot be updated after the shipping date has passed.', Response::HTTP_BAD_REQUEST);
        }

        $data = json_decode($request->getContent(), true);

        if (!empty($data['productId'])) {
            $product = $this->productRepository->findOneBy(['id' => $data['productId']]);
            $order->setProduct($product);
        }

        empty($data['quantity']) ? true : $order->setQuantity($data['quantity']);
        empty($data['address']) ? true : $order->setAddress($data['address']);

        $updatedOrder = $this->orderRepository->updateOrder($order);

        return new JsonResponse($updatedOrder->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/delete/{id}", name="delete_order", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $order = $this->orderRepository->findOneBy(['id' => $id]);

        if (empty($order)) {
            return new JsonResponse(['status' => 'Order not found.'], Response::HTTP_NOT_FOUND);
        }

        $this->orderRepository->removeOrder($order);

        return new JsonResponse(['status' => 'Order deleted.'], Response::HTTP_OK);
    }
}