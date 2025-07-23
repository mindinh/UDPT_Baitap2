<?php

require_once 'models/UserModel.php';

class LoginController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function showLoginPage($errorMessage = null) {
        $title = "Login";
        $error = $errorMessage ? $errorMessage : "";
        require_once 'views/login.php';
    }

    public function login($email, $password) {
        $user = $this->userModel->authenticate($email, $password);

        if ($user) {
            header('Location: index.php?action=home');
            $_SESSION['userId'] = $user['user_id']; 
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['user_type']; 
            
            exit();
        } else {
            $this->showLoginPage("Invalid email or password.");
        }
    }
}
