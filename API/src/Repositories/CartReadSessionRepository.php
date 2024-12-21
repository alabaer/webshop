<?php

namespace Fhtechnikum\Theshop\Repositories;

class CartReadSessionRepository
{
    private String $cartSessionName;
    private $cart;

    public function __construct($cartSessionName)
    {
        $this->cartSessionName = $cartSessionName;
        $this->cart = $this->aggregateCartFromSession();

    }

    private function aggregateCartFromSession()
    {
        if (!isset($_SESSION[$this->cartSessionName])) {
            $_SESSION[$this->cartSessionName] = [];
        }
        return $_SESSION[$this->cartSessionName];
    }

    public function getCart()
    {
        return $this->cart;
    }

}