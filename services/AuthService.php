<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../sessions/SessionManager.php';

class AuthService {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($username, $password) {
        $query = "SELECT * FROM user_m WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                SessionManager::login($user['id']);
                return true;
            }
        }

        return false;
    }

    public function logout() {
        SessionManager::logout();
        header('Location: /campa/admin/auth/login');
        exit();
    }
}
?>
