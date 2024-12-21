<?php

namespace Fhtechnikum\Theshop\Repositories;

use PDO;

class OrdersWriteRepository
{
    private $pdo;

    public function __construct(
        $host,
        $dbname,
        $user,
        $password
    )
    {
        $this->pdo = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $user, $password);
    }

    public function createOrder($orderNumber, $customerId, $orderDate, $totalPrice)
    {
        $sql = "INSERT INTO orders (order_number, customer_id, order_date, total_price) 
        VALUES(:orderNumber, :customerId, :orderDate, :totalPrice);";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':orderNumber', $orderNumber);
        $statement->bindParam(':customerId', $customerId);
        $statement->bindParam(':orderDate', $orderDate);
        $statement->bindParam(':totalPrice', $totalPrice);
        $statement->execute();

    }

    public function createPosition($orderNumber, $articleId, $amount)
    {
        $sql = "INSERT INTO order_positions (orders_id, product_id, amount) 
        VALUES(:ordersId, :articleId, :amount);";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':ordersId', $orderNumber);
        $statement->bindParam(':articleId', $articleId);
        $statement->bindParam(':amount', $amount);
        $statement->execute();

    }
}