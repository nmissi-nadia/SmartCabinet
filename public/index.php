<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Démarrer la session
session_start();

// Créer l'instance du routeur
$router = new Core\Router();

// Charger les routes
require_once __DIR__ . '/../app/config/routes.php';

// Gérer la requête
try {
    $router->dispatch($_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    // Log l'erreur
    error_log($e->getMessage());
    
    // Afficher une page d'erreur appropriée
    if ($e->getCode() === 404) {
        http_response_code(404);
        require_once __DIR__ . '/../app/Views/errors/404.php';
    } else {
        http_response_code(500);
        require_once __DIR__ . '/../app/Views/errors/500.php';
    }
}