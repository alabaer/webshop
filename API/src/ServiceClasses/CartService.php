<?php

namespace Fhtechnikum\Theshop\ServiceClasses;

use Fhtechnikum\Theshop\Models\CartModel;
use Fhtechnikum\Theshop\Repositories\ProductsRepository;
use SessionHandler;

class CartService
{
    private CartModel $cart;
    private const ID_MAX = 85;

    public function __construct()
    {
        session_start();

        $this->cart = new CartModel();

        $this->cart->session = new SessionHandler();

        $this->cart->items = $_SESSION['cart'] ?? [];

    }

    public function addItem(int $productId): array
    {

        $productRepository = new ProductsRepository($productId, DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);

        if ($productId <= self::ID_MAX) {
            $product = $productRepository->getProduct($productId);

            if (isset($this->cart->items[$productId])) {
                $this->cart->items[$productId]->quantity++;
            } else {
                $product->quantity = 1;
                $this->cart->items[$productId] = $product;
            }

            $_SESSION['cart'] = $this->cart->items;
        }
        if ($productId > self::ID_MAX) {
            return ['state' => 'ERROR'];
        }
        return ['state' => 'OK'];
    }

    public function removeItem(int $productId): array
    {
        if ($this->cart->items[$productId] == null) {
            return ['state' => 'ERROR'];
        }
        if (isset($this->cart->items[$productId])) {
            $this->cart->items[$productId]->quantity--;

            if ($this->cart->items[$productId]->quantity <= 0) {
                unset($this->cart->items[$productId]);
            }


            $_SESSION['cart'] = $this->cart->items;

        }

        return ['state' => 'OK'];
    }

    public function getItems(): array
    {
        return $this->cart->items;
    }
}
