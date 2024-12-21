<?php

namespace Fhtechnikum\Theshop\ServiceClasses;

use Fhtechnikum\Theshop\Repositories\OrdersReadRepository;
use Fhtechnikum\Theshop\Repositories\OrdersWriteRepository;


class OrderService
{
    private OrdersReadRepository $ordersReadRepository;
    private OrdersWriteRepository $ordersWriteRepository;


    public function __construct($orderReadRepository, $orderWriteRepository)
    {
        $this->ordersReadRepository = $orderReadRepository;
        $this->ordersWriteRepository = $orderWriteRepository;
    }

    public function getOrders($userId): array
    {
        return $this->ordersReadRepository->getOrders($userId);
    }

    public function createOrder($orderNumber, $customerId, $orderDate, $totalPrice)
    {
        $this->ordersWriteRepository->createOrder($orderNumber, $customerId, $orderDate, $totalPrice);
    }

    public function createOrderPosition($ordersId, $articleId, $amount)
    {
        $this->ordersWriteRepository->createPosition($ordersId, $articleId, $amount);
    }

}