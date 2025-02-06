<?php

use Core\Router;

$router = new Router();

// Routes publiques
$router->add('GET', '/SmartCabinet/public/', 'HomeController@index');
$router->add('GET', 'login', 'AuthController@login');
$router->add('POST', '/authenticate', 'AuthController@authenticate');
$router->add('GET', '/register', 'AuthController@register');
$router->add('POST', '/register', 'AuthController@store');
$router->add('GET', '/logout', 'AuthController@logout');

// Routes protégées - Patients
$router->add('GET', '/patients', 'PatientController@index');
$router->add('GET', '/patients/create', 'PatientController@create');
$router->add('POST', '/patients/create', 'PatientController@store');
$router->add('GET', '/patients/edit/{id}', 'PatientController@edit');
$router->add('POST', '/patients/update/{id}', 'PatientController@update');
$router->add('POST', '/patients/delete/{id}', 'PatientController@delete');

// Routes protégées - Médecins
$router->add('GET', '/medecins', 'MedecinController@index');
$router->add('GET', '/medecins/create', 'MedecinController@create');
$router->add('POST', '/medecins/store', 'MedecinController@store');
$router->add('GET', '/medecins/edit/{id}', 'MedecinController@edit');
$router->add('POST', '/medecins/update/{id}', 'MedecinController@update');
$router->add('POST', '/medecins/delete/{id}', 'MedecinController@delete');

// Routes protégées - Rendez-vous
$router->add('GET', '/rendez-vous', 'RendezVousController@index');
$router->add('GET', '/rendez-vous/create', 'RendezVousController@create');
$router->add('POST', '/rendez-vous/store', 'RendezVousController@store');
$router->add('GET', '/rendez-vous/edit/{id}', 'RendezVousController@edit');
$router->add('POST', '/rendez-vous/update/{id}', 'RendezVousController@update');
$router->add('POST', '/rendez-vous/delete/{id}', 'RendezVousController@delete');

// API Routes pour les requêtes AJAX
$router->add('GET', '/api/medecins/disponibilites/{id}', 'Api\MedecinController@disponibilites');
$router->add('GET', '/api/patients/search', 'Api\PatientController@search');
