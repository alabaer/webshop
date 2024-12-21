<?php

namespace Fhtechnikum\Theshop\ServiceClasses;

use Fhtechnikum\Theshop\Repositories\CategoriesReadRepository;

class CategoriesService
{
    private CategoriesReadRepository $categoriesRepository;

    public function __construct($categoriesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
    }

    public function getAllCategories(): array
    {
        return $this->categoriesRepository->aggregateCategories();
    }
}