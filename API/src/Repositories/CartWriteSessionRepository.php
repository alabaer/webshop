<?php

namespace Fhtechnikum\Theshop\Repositories;

use Fhtechnikum\Theshop\Models\CartModel;
use Fhtechnikum\Theshop\ServiceClasses\ProductsService;

class CartWriteSessionRepository
{
    private CartModel $cartModel;
    private array $cart;
    private string $cartSessionName;
    private const ID_MAX = 85;

    public function __construct($cartSessionName)
    {
        $this->cartSessionName = $cartSessionName;
        $this->cart = $this->aggregateCartFromSession();
        $this->cartModel = new CartModel();
        $this->cartModel->items = $this->cart;
    }

    private function aggregateCartFromSession(): array
    {
        if (!isset($_SESSION[$this->cartSessionName])) {
            $_SESSION[$this->cartSessionName] = [];
        }
        return $_SESSION[$this->cartSessionName];
    }

    public function addItem(int $productId): array
    {
        if ($productId > self::ID_MAX) {
            return ['state' => 'ERROR'];
        }
        $productRepository = new ProductsReadRepository(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);

        $productService = new ProductsService($productRepository);
        $product = $productService->getProduct($productId);

        if (!$this->itemExists($productId)) {
            $product->quantity = 0;
            $this->cartModel->items[$productId] = $product;
        }
        $this->cartModel->items[$productId]->quantity++;
        $this->storeSession();

        return ['state' => 'OK'];
    }

    private function itemExists(int $productId): bool
    {
        return isset($this->cartModel->items[$productId]);
    }

    public function removeItem(int $productId): array
    {
        if (!$this->itemExists($productId)) {
            return ['state' => 'ERROR'];
        }

        $this->cartModel->items[$productId]->quantity--;

        if ($this->amountIsZero($productId)) {
            unset($this->cartModel->items[$productId]);
        }
        $this->storeSession();
        return ['state' => 'OK'];
    }


    private function amountIsZero(int $productId): bool
    {
        return $this->cartModel->items[$productId]->quantity <= 0;
    }

    private function storeSession()
    {
        $_SESSION[$this->cartSessionName] = $this->cartModel->items;
    }
}
