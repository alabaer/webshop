<?php

namespace Fhtechnikum\Theshop\DTOs;

class OrderDTO
{
    public string $orderId;
    public string $date;

    public float $total;

    public static function map($order): OrderDTO
    {
        $orderDTO = new OrderDTO();
        $orderDTO->orderId = $order->orderNumber;
        $orderDTO->date = $order->date;
        $orderDTO->total = $order->totalPrice;
        return $orderDTO;

    }
}