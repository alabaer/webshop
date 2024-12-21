<?php

namespace Fhtechnikum\Theshop\Repositories;

use Fhtechnikum\Theshop\Models\ProductsModel;
use PDO;

class ProductsReadRepository
{
    private PDO $pdo;


    public function __construct($host, $dbname, $user, $password)
    {
        $this->pdo = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $password);
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

    public function aggregateProductsOfCategory($productTypeId): array
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
        return $this->mapProductsByCategory($productsList);
    }

    private function mapProductsByCategory(array $productsList): array
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
