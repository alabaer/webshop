<?php

namespace Fhtechnikum\Theshop\ServiceClasses;

use Fhtechnikum\Theshop\Models\ProductsModel;
use Fhtechnikum\Theshop\Repositories\ProductsReadRepository;

class ProductsService
{
    private ProductsReadRepository $productsReadRepository;

    public function __construct($productsReadRepository)
    {
        $this->productsReadRepository = $productsReadRepository;
    }

    public function getProductsByCategory($typeID): array
    {
        return $this->productsReadRepository->aggregateProductsOfCategory($typeID);
    }

    public function getProduct(int $productID): ProductsModel
    {
        return $this->productsReadRepository->getProduct($productID);
    }
}