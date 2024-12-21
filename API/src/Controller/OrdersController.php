<?php

namespace Fhtechnikum\Theshop\Controller;

use Exception;
use Fhtechnikum\Theshop\DTOs\OrderDTO;
use Fhtechnikum\Theshop\DTOs\OrdersArrayDTO;
use Fhtechnikum\Theshop\Repositories\OrdersReadRepository;
use Fhtechnikum\Theshop\Repositories\OrdersWriteRepository;
use Fhtechnikum\Theshop\ServiceClasses\OrderService;
use Fhtechnikum\Theshop\Views\JSONView;

require_once 'src/config/theShopConfig.php';

class OrdersController implements ControllerInterface
{
    private JSONView $jsonView;
    private OrdersReadRepository $ordersReadRepository;
    private OrdersWriteRepository $ordersWriteRepository;
    private OrderService $orderService;

    public function __construct()
    {
        $this->jsonView = new JSONView();
        $this->ordersReadRepository = new OrdersReadRepository(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $this->ordersWriteRepository = new OrdersWriteRepository(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $this->orderService = new OrderService($this->ordersReadRepository, $this->ordersWriteRepository);
    }

    public function route()
    {
        try {
            if (!isset($_SERVER['REQUEST_METHOD'])) {
                throw new Exception('Request method not set');
            }

            $requestMethod = $_SERVER['REQUEST_METHOD'];

            if (!isset($_SESSION[USER_SESSION_NAME]) || !isset($_SESSION[USER_SESSION_NAME][0]->userId)) {
                throw new Exception('User ID not found in session');
            }

            $userID = $_SESSION[USER_SESSION_NAME][0]->userId;

            switch ($requestMethod) {
                case 'GET':
                    $this->jsonView->output($this->aggregateOrders($userID));
                    break;
                case 'POST':
                    if (!isset($_SESSION[SHOPPING_CART_SESSION_NAME])) {
                        throw new Exception('Shopping cart session not found');
                    }

                    $cart = $_SESSION[SHOPPING_CART_SESSION_NAME];
                    $this->jsonView->output($this->createOrder($userID, $cart));
                    $this->emptyCartSession();
                    break;
                default:
                    http_response_code(405);
                    $this->jsonView->output(['error' => 'Unsupported request method']);
                    break;
            }
        } catch (Exception $e) {
            http_response_code(500);
            $this->jsonView->output(['Error' => $e->getMessage()]);
        }
    }

    private function aggregateOrders($userID): OrdersArrayDTO
    {
        $ordersList = $this->orderService->getOrders($userID);
        $dtoList = [];
        foreach ($ordersList as $order) {
            $dtoList[] = OrderDTO::map($order);
        }
        $ordersArray = new OrdersArrayDTO();
        $ordersArray->orders = $dtoList;
        return $ordersArray;

    }

    private function createOrder($userID, $cart): array
    {
        if ($userID == null || empty($cart)) {
            return ['state' => 'Error'];
        }
        $orderDate = date("Y-m-d H:i:s");
        $prefix = "Order: ";
        $orderNumber = uniqid($prefix);

        $totalPrice = $this->getTotalPrice($cart);
        $this->orderService->createOrder($orderNumber, $userID, $orderDate, $totalPrice);
        $this->writePositions($cart, $orderNumber);
        return ['state' => 'Okay'];
    }

    private function writePositions($cart, $orderNumber)
    {

        foreach ($cart as $productId => $product) {
            $id = $productId;
            $amount = $product->quantity;
            $this->orderService->createOrderPosition($orderNumber, $id, $amount);
        }
    }

    private function getTotalPrice($cart)
    {
        $totalPrice = 0;
        foreach ($cart as $product) {
            $totalPrice += $product->quantity * $product->price;
        }
        return $totalPrice;
    }

    private function emptyCartSession()
    {
        $_SESSION[SHOPPING_CART_SESSION_NAME] = [];
    }
}