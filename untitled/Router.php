<?php

class App
{
    public function route()
    {
        $action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);
        $ressource = filter_input(INPUT_GET, "ressource", FILTER_SANITIZE_STRING);

        if ($action) {
            return $this->routeByAction($action);
        }

        if ($ressource) {
            return $this->routeByRessource($ressource);
        }

        $this->sendNotFoundResponse();
    }

    private function routeByAction($action)
    {
        switch (strtolower($action)) {
            case 'login':
            case 'logout':
              //return UserDataController
            default:
                $this->sendNotFoundResponse();
        }
    }

    private function routeByRessource($ressource)
    {
        switch (strtolower($ressource)) {
            case 'product':
                //return ProudclistController
            case 'cart':
                // Handle resource related to cart
                // return CartController
            default:
                $this->sendNotFoundResponse();
        }
    }
}