<?php
namespace App\Controllers;

use App\Core\Application;
use App\Models\Patient;
use App\Models\Appointment;

class PatientController {
    public function dashboard() {
        // Vérifier si l'utilisateur est connecté
        if (!Application::$app->getSession()->get('user')) {
            Application::$app->getRouter()->redirect('/login');
        }
        
        // Récupérer les rendez-vous du patient
        $userId = Application::$app->getSession()->get('user');
        $appointments = Appointment::findAllByPatient($userId);
        
        return Application::$app->getRouter()->renderView('patient/dashboard', [
            'title' => 'Mon Espace Patient',
            'appointments' => $appointments
        ]);
    }
    
    public function profile() {
        if (!Application::$app->getSession()->get('user')) {
            Application::$app->getRouter()->redirect('/login');
        }
        
        $userId = Application::$app->getSession()->get('user');
        $patient = Patient::findOne(['id_utilisateur' => $userId]);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patient->loadData($_POST);
            if ($patient->update()) {
                Application::$app->getSession()->setFlash('success', 'Profil mis à jour avec succès');
                Application::$app->getRouter()->redirect('/patient/profile');
            }
        }
        
        return Application::$app->getRouter()->renderView('patient/profile', [
            'title' => 'Mon Profil',
            'patient' => $patient
        ]);
    }
    
    public function appointments() {
        if (!Application::$app->getSession()->get('user')) {
            Application::$app->getRouter()->redirect('/login');
        }
        
        $userId = Application::$app->getSession()->get('user');
        $appointments = Appointment::findAllByPatient($userId);
        
        return Application::$app->getRouter()->renderView('patient/appointments', [
            'title' => 'Mes Rendez-vous',
            'appointments' => $appointments
        ]);
    }
}
