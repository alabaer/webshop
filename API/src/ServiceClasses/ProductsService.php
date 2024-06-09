<?php

namespace Fhtechnikum\Theshop\ServiceClasses;

use Fhtechnikum\Theshop\Repositories\ProductsByCategoryIDRepository;

class ProductsService
{
    private ProductsByCategoryIDRepository $productsRepository;

    public function __construct($productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function getAllProducts($productTypeId): array
    {
        return $this->productsRepository->getProductsByCategoryID($productTypeId);
    }

}