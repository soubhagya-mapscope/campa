<?php
require_once  __DIR__ . '/../sessions/SessionManager.php';

class AuthMiddleware {
    public static function check() {
        if (!SessionManager::isLoggedIn()) {
            header('Location: /campa/admin/auth/login.php');
            exit();
        }
    }
}
?>
