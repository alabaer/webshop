<?php

namespace Fhtechnikum\Theshop\Repositories;

use Fhtechnikum\Theshop\Models\CategoriesModel;
use PDO;
class CategoriesRepository
{
    private PDO $pdo;

    public function __construct($host, $dbname, $user, $password)
    {
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    }

    public function getCategories(): array
    {
        $query = "SELECT id, name FROM product_types ORDER BY name";
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        $categoriesList = $statement->fetchAll();
        return $this->mapCategories($categoriesList);
    }

    private function mapCategories($categoriesList): array
    {
        $return = [];
        foreach ($categoriesList as $category) {
            $categoryModel = new CategoriesModel();
            $categoryModel->id = $category["id"];
            $categoryModel->name = $category["name"];
            $return[] = $categoryModel;
        }
        return $return;
    }
}