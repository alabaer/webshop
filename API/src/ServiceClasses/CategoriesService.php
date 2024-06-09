<?php

namespace Fhtechnikum\Theshop\ServiceClasses;

use Fhtechnikum\Theshop\Repositories\CategoriesRepository;

class CategoriesService
{
    private CategoriesRepository $categoriesRepository;

    public function __construct($categoriesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
    }

    public function getAllCategories(): array
    {
        return $this->categoriesRepository->getCategories();
    }
}