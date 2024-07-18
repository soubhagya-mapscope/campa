<?php
require_once __DIR__ . '/../sessions/SessionManager.php';

SessionManager::start();

$requestedPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Routes
$plantationListingRoute = '/campa/admin/plantation';
$loginRoute = '/campa/admin/auth/login';

// Check if user is logged in
if (SessionManager::isLoggedIn()) {
    // Redirect to plantation listing if trying to access /campa, /campa/admin, or /campa/admin/plantation
    if ($requestedPath === '/campa/' || $requestedPath === '/campa/admin/' || $requestedPath === '/campa/admin/plantation/') {
        header("Location: $plantationListingRoute");
        exit();
    }
} else {
    // Redirect to login page if trying to access /campa, /campa/admin, or any non-existent route
    if ($requestedPath === '/campa/' || $requestedPath === '/campa/admin/' || !file_exists(__DIR__ . '/../' . $requestedPath)) {
        header("Location: $loginRoute");
        exit();
    }
}

// If the requested path is valid and the user is logged in, allow the request to proceed
if ($requestedPath !== $plantationListingRoute && $requestedPath !== $loginRoute && file_exists(__DIR__ . '/../' . $requestedPath)) {
    return false; // Let Apache serve the requested file
}
?>
