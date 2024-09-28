<?php
// controllers/LoginController.php
require_once __DIR__ . '/../models/Login.php';

class LoginController
{
    private $loginModel;

    public function __construct($pdo)
    {
        $this->loginModel = new Login($pdo);
    }

    public function loginUser($data)
    {

        if (!isset($data['email']) && !isset($data['password'])) {
            return "Error: Faltan campos requeridos.";
        }

        return $this->loginModel->login($data);
    }
}
