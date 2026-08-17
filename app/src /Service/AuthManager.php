<?php

class AuthManager {
    
    private PDO $pdo;

    public function __construct(){
        $this->pdo = Database::getInstance()->pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login(string $email, string $motDePasse): bool{
        $sql = "
            SELECT *
            FROM utilisateur
            WHERE email = :email
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'email' => $email
        ]);

        $user = $stmt->fetch();
        if (!$user) {
            return false;
        }

        if (!password_verify($motDePasse, $user['mot_de_passe'])) {
            return false;
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        return true;
    }

    public function logout(): void{
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
    }

    public function isAuthenticated(): bool{
        return isset($_SESSION['user']);
    }

    public function getUser(): ?array{
        return $_SESSION['user'] ?? null;
    }

    public function getRole(): ?string{
        return $_SESSION['user']['role'] ?? null;
    }

    public function hasRole(string $role): bool{
        return $this->getRole() === $role;
    }

    public function hasAnyRole(array $roles): bool{
        $role = $this->getRole();
        return $role !== null
            && in_array($role, $roles, true);
    }

    public function requireAuth(): void{
        if (!$this->isAuthenticated()) {
            header('Location: /login');
            exit;
        }
    }

    public function requireRole(string $role): void{
        $this->requireAuth();
        if (!$this->hasRole($role)) {
            http_response_code(403);
            exit('Accès interdit.');
        }
    }

    public function requireAnyRole(array $roles): void{
        $this->requireAuth();
        if (!$this->hasAnyRole($roles)) {
            http_response_code(403);
            exit('Accès interdit.');
        }
    }
}