<?php

namespace Fhtechnikum\Theshop\Controller;

use Exception;
use Fhtechnikum\Theshop\Repositories\UserReadRepository;
use Fhtechnikum\Theshop\Repositories\UserWriteSessionRepository;
use Fhtechnikum\Theshop\ServiceClasses\UserService;
use Fhtechnikum\Theshop\Views\JSONView;

require_once 'src/config/theShopConfig.php';

class UserController implements ControllerInterface
{
    private JSONView $jsonView;

    public function __construct()
    {
        $this->jsonView = new JSONView();
    }

    public function route()
    {
        try {
            if (!isset($_GET['action'])) {
                throw new Exception('No action specified');
            }

            $action = $_GET['action'];
            switch ($action) {
                case 'login':
                    $this->jsonView->output($this->login());
                    break;
                case 'logout':
                    $this->jsonView->output($this->logout());
                    break;
                default:
                    throw new Exception('Invalid action');
            }
        } catch (Exception $e) {
            $this->jsonView->output(['state' => 'Error', 'message' => $e->getMessage()]);
        }
    }

    private function login()
    {
        if ($this->logedIn()) {
            return ['state' => 'Error'];
        }
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        $readRepository = new UserReadRepository(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
        $writeRepository = new UserWriteSessionRepository(USER_SESSION_NAME);
        $service = new UserService($readRepository, $writeRepository);

        $user = $service->getUserData($username, $password);
        if ($user === null) {
            return ['state' => 'ERROR'];
        }
        $service->storeUserIdInSession($user->userId, $user->username);
        return ['state' => 'OK'];

    }

    private function logedIn(): bool
    {
        return !empty($_SESSION[USER_SESSION_NAME]);
    }

    private function logout(): array
    {
        if (!$this->sessionExists()) {
            return ['state' => 'LOGEDIN'];
        }
        session_unset();
        $this->destroySessionCookie();
        session_destroy();
        return ['state' => 'OK'];
    }

    private function destroySessionCookie()
    { //Aus PhP Manual rauskopiert um Session Cookie zu löschen
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]);
        }
    }

    private function sessionExists(): bool
    {
        return isset($_SESSION[USER_SESSION_NAME]);
    }
}