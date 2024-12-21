<?php

namespace Fhtechnikum\Theshop\DTOs;

class ProductDTO
{
    public string $name;
    public int $id;
    public string $url;

    public static function map($product): ProductDTO
    {
        $productDTO = new ProductDTO();
        if ($product->name !== null) {
            $productDTO->name = $product->name;
            $productDTO->id = $product->id;
            $productDTO->url = "http://localhost/theShop/API/index.php?resource=types";
        }
        return $productDTO;
    }
}