<?php

class AuthController
{
    private AuthManager $auth;

    public function __construct()
    {
        $this->auth = new AuthManager();
    }

    public function showLogin(): void
    {
        $error = null;

        require dirname(__DIR__, 2) . '/views/auth/login.php';
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        if ($this->auth->login($email, $motDePasse)) {
            header('Location: /');
            exit;
        }

        $error = "Email ou mot de passe incorrect.";

        require dirname(__DIR__, 2) . '/views/auth/login.php';
    }

    public function logout(): void
    {
        $this->auth->logout();

        header('Location: /login');
        exit;
    }
}