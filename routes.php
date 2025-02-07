<?php

// Routes d'authentification
$router->get('/auth/login', 'AuthController@login');
$router->post('/auth/login', 'AuthController@login');
$router->get('/auth/register', 'AuthController@register');
$router->post('/auth/register', 'AuthController@register');
$router->get('/auth/logout', 'AuthController@logout');

// Routes pour les médecins
$router->get('/medecin/dashboard', 'MedecinController@dashboard');
$router->post('/medecin/update-disponibilite', 'MedecinController@updateDisponibilite');
$router->post('/medecin/confirmer-rdv', 'MedecinController@confirmerRendezVous');
$router->post('/medecin/annuler-rdv', 'MedecinController@annulerRendezVous');
$router->get('/medecin/patients', 'MedecinController@listePatients');
$router->get('/medecin/patient/{id}', 'MedecinController@detailPatient');
$router->get('/medecin/profil', 'MedecinController@profil');
$router->post('/medecin/profil', 'MedecinController@updateProfil');

// Routes pour les patients
$router->get('/patient/dashboard', 'PatientController@dashboard');
$router->post('/patient/prendre-rdv', 'PatientController@prendreRendezVous');
$router->post('/patient/annuler-rdv', 'PatientController@annulerRendezVous');
$router->get('/patient/profil', 'PatientController@profil');
$router->post('/patient/profil', 'PatientController@updateProfil');

// Route par défaut
$router->get('/', function() {
    header('Location: /auth/login');
    exit;
});
