<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Application;

$app = new Application();

// Routes d'authentification
$app->router->get('/auth/login', 'AuthController@loginForm');
$app->router->post('/auth/login', 'AuthController@login');
$app->router->get('/auth/register', 'AuthController@registerForm');
$app->router->post('/auth/register', 'AuthController@register');
$app->router->get('/auth/logout', 'AuthController@logout');

// Routes patient
$app->router->get('/patient/dashboard', 'PatientController@dashboard');
$app->router->get('/patient/profile', 'PatientController@profile');
$app->router->post('/patient/profile', 'PatientController@profile');
$app->router->get('/patient/appointments', 'PatientController@appointments');
$app->router->post('/patient/appointment/cancel', 'PatientController@cancelAppointment');

// Routes médecin
$app->router->get('/medecin/dashboard', 'MedecinController@dashboard');
$app->router->get('/medecin/profile', 'MedecinController@profile');
$app->router->post('/medecin/profile', 'MedecinController@profile');
$app->router->get('/medecin/appointment/confirm/{id}', 'MedecinController@confirmAppointment');
$app->router->get('/medecin/appointment/cancel/{id}', 'MedecinController@cancelAppointment');

// Route par défaut
$app->router->get('/', 'SiteController@home');

$app->run();