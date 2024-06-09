<?php

namespace Fhtechnikum\Theshop\Repositories;

use Fhtechnikum\Theshop\Models\ProductsModel;
use PDO;

class ProductsRepository
{
    private PDO $pdo;
    private int $productId;

    public function __construct($productId, $host, $dbname, $username, $password)
    {
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $this->productId = $productId;
    }

    public function getProduct($productTypeId): ProductsModel
    {
        $sql = "SELECT name AS productName, 
                       price_of_sale AS productPrice,
                       id AS productID
                FROM products
                WHERE id = :id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':id', $productTypeId, PDO::PARAM_INT);
        $statement->execute();

        $productDB = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $this->mapProduct($productDB);
    }

    private function mapProduct(array $productDB): ProductsModel
    {
        $product = new ProductsModel();
        $product->name = $productDB[0]['productName'];
        $product->id = $productDB[0]['productID'];
        $product->price = $productDB[0]['productPrice'];

        return $product;
    }
}
