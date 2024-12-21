<?php

namespace Fhtechnikum\Theshop\DTOs;

class CartDTO
{
    public float $total;
    public array $cart;


    public static function map($product): CartItemDTO
    {
        $cartDTO = new CartItemDTO();
        $cartDTO->articleName = $product->name;
        $cartDTO->amount = $product->quantity;
        $cartDTO->price = number_format($product->price, 2);
        $cartDTO->id = $product->id;
        $cartDTO->amountPrice = number_format($product->price * $product->quantity, 2);
        return $cartDTO;
    }
}