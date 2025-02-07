<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Core\Application;
use App\Controllers\AuthController;
use App\Controllers\PatientController;
use App\Controllers\MedecinController;
use App\Controllers\SiteController;

$app = new Application();

// Routes d'authentification
$app->router->get('/auth/login', [AuthController::class, 'login']);
$app->router->post('/auth/login', [AuthController::class, 'login']);
$app->router->get('/auth/register', [AuthController::class, 'register']);
$app->router->post('/auth/register', [AuthController::class, 'register']);
$app->router->get('/auth/logout', [AuthController::class, 'logout']);

// Routes patient
$app->router->get('/patient/dashboard', [PatientController::class, 'dashboard']);
$app->router->get('/patient/profile', [PatientController::class, 'profile']);
$app->router->post('/patient/profile', [PatientController::class, 'profile']);
$app->router->get('/patient/appointments', [PatientController::class, 'appointments']);
$app->router->post('/patient/appointment/cancel', [PatientController::class, 'cancelAppointment']);

// Routes médecin
$app->router->get('/medecin/dashboard', [MedecinController::class, 'dashboard']);
$app->router->get('/medecin/profile', [MedecinController::class, 'profile']);
$app->router->post('/medecin/profile', [MedecinController::class, 'profile']);
$app->router->get('/medecin/appointment/confirm/{id}', [MedecinController::class, 'confirmAppointment']);
$app->router->get('/medecin/appointment/cancel/{id}', [MedecinController::class, 'cancelAppointment']);

// Redirection par défaut vers la page de connexion
$app->router->get('/', [AuthController::class, 'login']);

$app->run();