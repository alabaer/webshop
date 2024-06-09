<?php

namespace Fhtechnikum\Theshop\Repositories;
use Fhtechnikum\Theshop\Models\ProductsModel;
use PDO;

class ProductsByCategoryIDRepository
{
    private PDO $pdo;
    private int $productTypeId;

    public function __construct($productTypeId, $host, $dbname, $user, $password)
    {
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $this->productTypeId = $productTypeId;
    }

    public function getProductsByCategoryID($productTypeId): array
    {
        $sql = "SELECT t.name AS productTypeName, 
                       p.name AS productName, 
                       p.price_of_sale AS productPrice,
                        p.id AS productID
                FROM product_types t 
                LEFT JOIN products p ON t.id = p.id_product_types
                WHERE t.id = :id";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':id', $productTypeId, PDO::PARAM_INT);
        $statement->execute();

        $productsList = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $this->mapProducts($productsList);
    }

    private function mapProducts(array $productsList): array
    {
        $return = [];
        foreach ($productsList as $product) {
            $productModel = new ProductsModel();
            $productModel->categoryName = $product['productTypeName'];
            $productModel->name = $product['productName'];
            $productModel->id = $product['productID'];
            $return[] = $productModel;
        }
        return $return;
    }
}