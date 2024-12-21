<?php

namespace Fhtechnikum\Theshop\Controller;

use Exception;
use Fhtechnikum\Theshop\DTOs\CartDTO;
use Fhtechnikum\Theshop\Repositories\CartReadSessionRepository;
use Fhtechnikum\Theshop\Repositories\CartWriteSessionRepository;
use Fhtechnikum\Theshop\ServiceClasses\CartService;
use Fhtechnikum\Theshop\Views\JSONView;
use InvalidArgumentException;

require_once 'src/config/theShopConfig.php';

class CartController implements ControllerInterface
{
    private CartService $cartService;
    private JSONView $jsonView;

    public function __construct()
    {
        $cartReadSessionRepository = new CartReadSessionRepository(SHOPPING_CART_SESSION_NAME);
        $cartWriteSessionRepository = new CartWriteSessionRepository(SHOPPING_CART_SESSION_NAME);
        $this->cartService = new CartService($cartReadSessionRepository, $cartWriteSessionRepository);
        $this->jsonView = new JSONView();
    }

    public function route()
    {

        $requestMethod = $_SERVER['REQUEST_METHOD'];
        if ($this->articleIdinRequest()) {
            $articleId = filter_var($_GET['articleId'], FILTER_VALIDATE_INT);
        }
        try {
            switch ($requestMethod) {
                case 'POST':
                    if ($articleId === false || $articleId === null) {
                        throw new InvalidArgumentException('Invalid article ID');
                    }
                    $this->jsonView->output($this->addItem($articleId));
                    break;

                case 'DELETE':
                    if ($articleId === false || $articleId === null) {
                        throw new InvalidArgumentException('Invalid article ID');
                    }
                    $this->jsonView->output($this->removeItem($articleId));
                    break;

                case 'GET':
                    $this->jsonView->output($this->getCartData());
                    break;

                default:
                    http_response_code(405);
                    $this->jsonView->output(['error' => 'Unsupported request method']);
                    break;
            }
        } catch (InvalidArgumentException $e) {
            http_response_code(400);
            $this->jsonView->output(['error' => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500);
            $this->jsonView->output(['error' => 'Unknown error']);
        }
    }

    private function articleIdinRequest(): bool
    {
        return isset($_GET['articleId']);
    }

    private function addItem($articleId): array
    {
        return $this->cartService->addItem($articleId);
    }

    private function removeItem($articleId): array
    {
        return $this->cartService->removeItem($articleId);
    }

    private function getCartData(): CartDTO
    {
        $cartItems = $this->cartService->getItems();
        $dtoList = [];
        $totalPrice = 0;
        foreach ($cartItems as $product) {
            $totalPrice += $product->price * $product->quantity;
            $dtoList[] = CartDTO::map($product);
        }
        $cartDTO = new CartDTO();
        $cartDTO->cart = $dtoList;
        $cartDTO->total = $totalPrice;
        return $cartDTO;
    }
}
