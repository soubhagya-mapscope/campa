<?php
require_once __DIR__ . '/../../services/AuthService.php';

$authService = new AuthService();
$authService->logout();
?>
