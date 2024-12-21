<?php

namespace Fhtechnikum\Theshop\Controller;

use Exception;
use Fhtechnikum\Theshop\DTOs\CategoriesDTO;
use Fhtechnikum\Theshop\DTOs\ProductDTO;
use Fhtechnikum\Theshop\DTOs\ProductsArrayDTO;
use Fhtechnikum\Theshop\Repositories\CategoriesReadRepository;
use Fhtechnikum\Theshop\Repositories\ProductsReadRepository;
use Fhtechnikum\Theshop\ServiceClasses\CategoriesService;
use Fhtechnikum\Theshop\ServiceClasses\ProductsService;
use Fhtechnikum\Theshop\Views\JSONView;
use InvalidArgumentException;

require_once 'src/config/theShopConfig.php';

class ProductsListController implements ControllerInterface
{
    private CategoriesService $categoryService;
    private JSONView $jsonView;
    private CategoriesReadRepository $categoriesRepository;

    public function __construct()
    {
        $this->jsonView = new JSONView();
        $this->categoriesRepository = new CategoriesReadRepository(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $this->categoryService = new CategoriesService($this->categoriesRepository);
    }

    public function route()
    {
        try {

            $resource = filter_input(INPUT_GET, "resource", FILTER_SANITIZE_STRING);
            switch ($resource) {
                case "types":

                    $this->jsonView->output($this->listCategories());
                    break;
                case "products":
                    $filterType = filter_var($_GET['filter-type'], FILTER_VALIDATE_INT);
                    if ($filterType === null) {
                        throw new InvalidArgumentException("Invalid filter-type parameter");
                    }

                    $this->jsonView->output($this->listProducts($filterType));
                    break;

                default:
                    throw new InvalidArgumentException("Invalid value for resource parameter");
            }
        } catch (InvalidArgumentException $e) {
            http_response_code(400);
            $this->jsonView->output(['error' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500);
            $this->jsonView->output(['error' => $e->getMessage()]);
        }
    }


    private function listCategories(): array
    {
        $categoryList = $this->categoryService->getAllCategories();
        $dtoList = [];
        foreach ($categoryList as $category) {
            $dtoList[] = CategoriesDTO::map($category);
        }
        return $dtoList;
    }

    private function listProducts($filterType): ProductsArrayDTO
    {
        $productsRepository = new ProductsReadRepository(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $productService = new ProductsService($productsRepository);
        $productsList = $productService->getProductsByCategory($filterType);
        $dtoList = [];
        foreach ($productsList as $product) {
            $dtoList[] = ProductDTO::map($product);
        }

        $productsDTO = new ProductsArrayDTO();
        $productsDTO->products = $dtoList;
        $productsDTO->categoryName = $productsList[0]->categoryName;
        return $productsDTO;
    }
}
