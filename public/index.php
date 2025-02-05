<?php

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
session_start();
$router = new Core\Router();
require_once __DIR__ . '/../app/config/routes.php';



try {
    $router->dispatch($_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    error_log($e->getMessage());

    if ($e->getCode() === 404) {
        http_response_code(404);
        require_once __DIR__ . '/../app/Views/errors/404.php';
    } else {
        http_response_code(500);
        require_once __DIR__ . '/../app/Views/errors/500.php';
    }
}