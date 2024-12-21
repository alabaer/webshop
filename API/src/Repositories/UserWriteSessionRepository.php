<?php

namespace Fhtechnikum\Theshop\Repositories;

use Fhtechnikum\Theshop\Models\UserModel;

class UserWriteSessionRepository
{
    private $user;
    private string $userSessionName;

    public function __construct($userSessionName)
    {
        $this->userSessionName = $userSessionName;
        $this->user = $this->getUserInSession();
    }

    public function getUserInSession()
    {

        if (!isset($_SESSION[$this->userSessionName])) {
            $_SESSION[$this->userSessionName] = [];
        }
        return $_SESSION[$this->userSessionName];
    }

    public function storeUserInSession($userId, $username): array
    {
        $user = new UserModel();
        $user->userId = $userId;
        $user->username = $username;

        return $_SESSION[$this->userSessionName] = [$user];
    }
}