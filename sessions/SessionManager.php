<?php
class SessionManager {
    public static function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login($userId) {
        self::start();
        $_SESSION['user_id'] = $userId;
    }

    public static function logout() {
        self::start();
        session_destroy();
    }

    public static function isLoggedIn() {
        self::start();
        return isset($_SESSION['user_id']);
    }
}
?>
