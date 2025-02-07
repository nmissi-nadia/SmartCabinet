<?php
namespace App\Controllers;

use App\Core\Application;
use App\Models\Medecin;

class HomeController {
    public function index() {
        // Récupérer la liste des médecins pour l'affichage
        $medecins = Medecin::findAll();
        
        return Application::$app->getRouter()->renderView('home', [
            'title' => 'Accueil - Cabinet Médical',
            'medecins' => $medecins
        ]);
    }
}
