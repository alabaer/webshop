<?php

namespace Fhtechnikum\Theshop;

use Exception;
use Fhtechnikum\Theshop\DTOs\CartDTO;
use Fhtechnikum\Theshop\DTOs\CategoriesDTO;
use Fhtechnikum\Theshop\DTOs\ProductsArrayDTO;
use Fhtechnikum\Theshop\Repositories\CategoriesRepository;
use Fhtechnikum\Theshop\Repositories\ProductsByCategoryIDRepository;
use Fhtechnikum\Theshop\ServiceClasses\CategoriesService;
use Fhtechnikum\Theshop\ServiceClasses\CartService;
use Fhtechnikum\Theshop\ServiceClasses\ProductsService;
use Fhtechnikum\Theshop\Views\JSONView;
use InvalidArgumentException;

require_once 'src/config/theShopConfig.php';

class Controller
{
    private CategoriesService $categoryService;
    private JSONView $jsonView;
    private CategoriesRepository $categoriesRepository;
    private CartService $cartService;

    public function __construct()
    {
        $this->jsonView = new JSONView();
        $this->categoriesRepository = new CategoriesRepository(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $this->categoryService = new CategoriesService($this->categoriesRepository);
        $this->cartService = new CartService();
    }

    public function route()
    {
        try {
            if (!isset($_GET["resource"])) {
                throw new InvalidArgumentException("Invalid resource parameter");
            }

            $resource = strtolower($_GET["resource"]);
            switch ($resource) {
                case "types":

                    $this->jsonView->output($this->listCategories());
                    break;
                case "products":
                    $filterType = filter_var($_GET['filter-type'], FILTER_VALIDATE_INT) ?? null;
                    if ($filterType === false) {
                        throw new InvalidArgumentException("Invalid filter-type parameter");
                    }

                    $this->jsonView->output($this->listProducts($filterType));
                    break;
                case "cart":
                    $articleId = isset($_GET['articleId']) ? filter_var($_GET['articleId'], FILTER_VALIDATE_INT) : null;
                    if ($articleId === false) {
                        throw new InvalidArgumentException("Invalid articleId parameter");
                    }
                    $this->jsonView->output($this->handleCart($articleId));
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
        $productsRepository = new ProductsByCategoryIDRepository($filterType, DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $productsService = new ProductsService($productsRepository);
        $productsList = $productsService->getAllProducts($filterType);
        $dtoList = [];
        foreach ($productsList as $product) {
            $dtoList[] = ProductsArrayDTO::map($product);
        }

        $productsDTO = new ProductsArrayDTO();
        $productsDTO->products = $dtoList;
        $productsDTO->categoryName = $productsList[0]->categoryName;
        return $productsDTO;
    }

    private function handleCart($articleId)
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                if ($articleId === false) {
                    throw new InvalidArgumentException('state: ERROR');
                }

                return $this->cartService->addItem($articleId);

            case 'DELETE':
                $articleId = filter_var($_GET['articleId'], FILTER_VALIDATE_INT) ?? null;
                if ($articleId === false) {
                    throw new InvalidArgumentException('state: ERROR');
                }
                return $this->cartService->removeItem($articleId);

            case 'GET':
                $cartItems = $this->cartService->getItems();
                $dtoList = [];
                foreach ($cartItems as $product) {

                    $dtoList[] = CartDTO::map($product);
                }
                $cartDTO = new CartDTO();
                $cartDTO->cart = $dtoList;
                return $cartDTO;

            default:
                throw new InvalidArgumentException("Unsupported request method");
        }
    }
}
