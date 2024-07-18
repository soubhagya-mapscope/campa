<?php
session_start();

class SessionManager {
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function login($user_id) {
        $_SESSION['user_id'] = $user_id;
    }

    public static function logout() {
        session_destroy();
    }
}
?>
