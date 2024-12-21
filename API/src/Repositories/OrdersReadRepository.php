<?php

namespace Fhtechnikum\Theshop\Repositories;

use Fhtechnikum\Theshop\Models\OrderModel;
use PDO;

class OrdersReadRepository
{
    private PDO $pdo;

    public function __construct($host, $dbname, $user, $password)
    {
        $this->pdo = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $password);
    }

    public function getOrders($userId): array
    {
        $sql = "SELECT order_number, order_date, total_price FROM `orders` WHERE customer_id =:userId";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->execute();

        $ordersList = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $this->mapOrders($ordersList);
    }

    private function mapOrders($ordersList): array
    {
        $return = [];
        foreach ($ordersList as $order) {
            $orderModel = new OrderModel();
            $orderModel->orderNumber = $order['order_number'];
            $orderModel->date = $order['order_date'];
            $orderModel->totalPrice = $order['total_price'];
            $return[] = $orderModel;
        }
        return $return;
    }

}