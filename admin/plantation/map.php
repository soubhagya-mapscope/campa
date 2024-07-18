<?php
require_once '../../middleware/AuthMiddleware.php';
require_once '../../services/PlantationService.php';

AuthMiddleware::check();

if (isset($_GET['id'])) {
    echo 'valid request.';
    ?>

<?php
} else {
    echo 'Invalid request.';
}
?>