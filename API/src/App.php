<?php

namespace Fhtechnikum\Theshop;

use Fhtechnikum\Theshop\Controller\CartController;
use Fhtechnikum\Theshop\Controller\OrdersController;
use Fhtechnikum\Theshop\Controller\ProductsListController;
use Fhtechnikum\Theshop\Controller\UserController;

class App
{
    public function start()
    {
        session_start();
        $controller = $this->identifyRoute();
        $controller->route();
    }

    private function identifyRoute()
    {
        $resource = filter_input(INPUT_GET, "resource", FILTER_SANITIZE_STRING);
        $action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);

        if ($resource !== null) {
            return $this->resourceControllers($resource);
        }
        if ($action !== null) {
            return $this->actionControllers($action);
        }
        return http_response_code(404);
    }

    private function resourceControllers($resource)
    {

        switch (strtolower($resource)) {
            case "types":
            case "products":
                return new ProductsListController();
            case "cart":
                return new CartController();
            case"orders":
                return new OrdersController();
            default:
                http_response_code(404);
                die();
        }
    }

    private function actionControllers($action)
    {
        switch (strtolower($action)) {
            case "login":
            case"logout":
                return new UserController();
            default:
                http_response_code(404);
                die();
        }
    }
}