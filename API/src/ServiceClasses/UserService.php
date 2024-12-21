<?php

namespace Fhtechnikum\Theshop\ServiceClasses;

use Fhtechnikum\Theshop\Models\UserModel;
use Fhtechnikum\Theshop\Repositories\UserReadRepository;
use Fhtechnikum\Theshop\Repositories\UserWriteSessionRepository;

require_once 'src/config/theShopConfig.php';

class UserService
{
    private UserReadRepository $userDataRepository;
    private UserWriteSessionRepository $userDataWriteSessionRepository;

    public function __construct($userReadRepository, $userDataWriteSessionRepository)
    {
        $this->userDataRepository = $userReadRepository;
        $this->userDataWriteSessionRepository = $userDataWriteSessionRepository;
    }

    public function getUserData($username, $password): ?UserModel
    {
        return $this->userDataRepository->getUser($username, $password);
    }

    public function storeUserIdInSession($userId, $username)
    {
        $this->userDataWriteSessionRepository->storeUserInSession($userId, $username);
    }
}