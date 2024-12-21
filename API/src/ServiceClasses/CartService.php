<?php

namespace Fhtechnikum\Theshop\ServiceClasses;

use Fhtechnikum\Theshop\Repositories\CartReadSessionRepository;
use Fhtechnikum\Theshop\Repositories\CartWriteSessionRepository;

require_once 'src/config/theShopConfig.php';

class CartService
{
    private CartReadSessionRepository $cartReadSessionRepository;
    private CartWriteSessionRepository $cartWriteSessionRepository;

    public function __construct($cartReadSessionRepository, $cartWriteSessionRepository)
    {

        $this->cartReadSessionRepository = $cartReadSessionRepository;
        $this->cartWriteSessionRepository = $cartWriteSessionRepository;


    }

    public function addItem(int $productId): array
    {
        return $this->cartWriteSessionRepository->addItem($productId);
    }


    public
    function removeItem(int $productId): array
    {
        return $this->cartWriteSessionRepository->removeItem($productId);
    }

    public
    function getItems(): array
    {
        return $this->cartReadSessionRepository->getCart();
    }
}
