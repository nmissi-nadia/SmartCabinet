<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Core\Application;

class PatientController {
    private Database $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function dashboard() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Patient') {
            header('Location: ' . Application::$app->getBaseUrl() . '/auth/login');
            exit;
        }

        try {
            $db = Application::$app->getDatabase();
            
            // Récupérer les informations du patient
            $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $patient = $stmt->fetch();

            if (!$patient) {
                header('Location: ' . Application::$app->getBaseUrl() . '/auth/login');
                exit;
            }

            // Récupérer les rendez-vous du patient
            $rendezVous = RendezVous::findAllByPatient($_SESSION['user_id']);

            require_once __DIR__ . '/../views/patient/dashboard.php';
        } catch (\Exception $e) {
            header('Location: ' . Application::$app->getBaseUrl() . '/auth/login');
            exit;
        }
    }

    public function prendreRendezVous() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_patient = $_SESSION['user_id'];
            $id_medecin = $_POST['id_medecin'] ?? '';
            $date_rdv = $_POST['date_rdv'] ?? '';
            $commentaire = $_POST['commentaire'] ?? '';

            try {
                $stmt = $this->db->prepare("
                    INSERT INTO rendez_vous (id_patient, id_medecin, date_rdv, commentaire, statut)
                    VALUES (?, ?, ?, ?, 'En attente')
                ");
                $stmt->execute([$id_patient, $id_medecin, $date_rdv, $commentaire]);
            } catch (\Exception $e) {
            }
        }
    }

    public function annulerRendezVous() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_rdv = $_POST['id_rdv'] ?? '';
            $id_patient = $_SESSION['user_id'];

            try {
                $stmt = $this->db->prepare("
                    UPDATE rendez_vous 
                    SET statut = 'Annulé'
                    WHERE id_rdv = ? AND id_patient = ?
                ");
                $stmt->execute([$id_rdv, $id_patient]);
            } catch (\Exception $e) {
            }
        }
    }

    public function profile() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Patient') {
            header('Location: ' . Application::$app->getBaseUrl() . '/auth/login');
            exit;
        }

        try {
            $db = Application::$app->getDatabase();
            
            // Récupérer les informations du patient
            $stmt = $db->prepare("SELECT * FROM patients WHERE id_patient = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $patient = $stmt->fetch();

            if (!$patient) {
                header('Location: ' . Application::$app->getBaseUrl() . '/auth/login');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Mettre à jour le profil
                $stmt = $db->prepare("
                    UPDATE patients 
                    SET telephone = ?
                    WHERE id_patient = ?
                ");
                $stmt->execute([
                    $_POST['telephone'],
                    $_SESSION['user_id']
                ]);

                header('Location: ' . Application::$app->getBaseUrl() . '/patient/profile');
                exit;
            }

            require_once __DIR__ . '/../views/patient/profile.php';
        } catch (\Exception $e) {
            header('Location: ' . Application::$app->getBaseUrl() . '/patient/dashboard');
            exit;
        }
    }
    
    public function appointments() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /SmartCabinet/auth/login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $rendezVous = RendezVous::findAllByPatient($userId);
        
        require_once __DIR__ . '/../views/patient/appointments.php';
    }
}
