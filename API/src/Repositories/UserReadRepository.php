<?php

namespace Fhtechnikum\Theshop\Repositories;

use Fhtechnikum\Theshop\Models\UserModel;
use PDO;

class UserReadRepository
{
    private PDO $pdo;


    public function __construct($host, $dbname, $username, $password)
    {
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    }

    public function getUser($username, $password): ?UserModel
    {
        $sql = "SELECT * FROM customers WHERE username = :username AND password = PASSWORD(:password);";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':username', $username);
        $statement->bindParam(':password', $password);
        $statement->execute();
        $userDB = $statement->fetch(PDO::FETCH_ASSOC);
        if ($userDB === false) {
            return null;
        }
        return $this->mapUser($userDB);
    }

    private function mapUser($userDB): UserModel
    {
        $user = new UserModel();
        $user->userId = $userDB['id'];
        $user->username = $userDB['username'];
        return $user;
    }

}