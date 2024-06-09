<?php

namespace Fhtechnikum\Theshop\DTOs;

class CategoriesDTO
{
    public string $productType;
    public string $url;

    public static function map($category): CategoriesDTO
    {
        $categoryDTO = new CategoriesDTO();
        $categoryDTO->productType = $category->name;
        $categoryDTO->url = "http://localhost/theShop/API/index.php?resource=products&filter-type=" . $category->id;
        return $categoryDTO;
    }
}