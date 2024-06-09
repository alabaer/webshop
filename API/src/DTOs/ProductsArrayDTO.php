<?php

namespace Fhtechnikum\Theshop\DTOs;

class ProductsArrayDTO
{
    public string $categoryName;
    public array $products;

    public static function map($product): array
    {
        $productDTO = [];
        if ($product->name !== null) {
            $productDTO['name'] = $product->name;
            $productDTO['id'] = $product->id;
            $productDTO['url'] = "http://localhost/theShop/API/index.php?resource=types";
        }
        return $productDTO;
    }
}