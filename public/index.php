<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/config/routes.php';

use App\Core\Router;

$router = new Router();
$router->dispatch($_SERVER['REQUEST_URI']);
?>