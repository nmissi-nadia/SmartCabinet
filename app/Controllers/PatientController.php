<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\Patient;
use App\Models\User;
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

        $baseUrl = Application::$app->getBaseUrl();
        $db = Application::$app->getDatabase();
        
        // Récupérer les informations du patient
        $user = User::findOne(['id_utilisateur' => $_SESSION['user_id']]);
        $patient = Patient::findByUserId($_SESSION['user_id']);
        
        if (!$user || !$patient) {
            header('Location: ' . $baseUrl . '/auth/login');
            exit;
        }
        
        // Récupérer les rendez-vous du patient
        $stmt = $db->prepare("
            SELECT r.*, 
                   m.nom as medecin_nom, m.prenom,
                   im.specialite
            FROM rendez_vous r
            JOIN utilisateurs m ON r.id_medecin = m.id_utilisateur
            JOIN infos_medecins im ON m.id_utilisateur = im.id_utilisateur
            WHERE r.id_patient = ?
            ORDER BY r.date_rdv DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, RendezVous::class);
        $rendezVous = $stmt->fetchAll();

        require_once __DIR__ . '/../views/patient/dashboard.php';
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

        $baseUrl = Application::$app->getBaseUrl();
        $message = '';
        $error = '';

        try {
            $user = User::findOne(['id_utilisateur' => $_SESSION['user_id']]);
            $patient = Patient::findByUserId($_SESSION['user_id']);
            if (!$user || !$patient) {
                throw new \Exception("Utilisateur ou informations patient non trouvés");
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $user->nom = $_POST['nom'] ?? $user->nom;
                $user->prenom = $_POST['prenom'] ?? $user->prenom;
                $user->email = $_POST['email'] ?? $user->email;

                // Mettre à jour les informations du patient
                $patient->numero_secu = $_POST['numero_secu'] ?? $patient->numero_secu;

                if ($user->validate() && $patient->validate()) {
                    if ($user->update() && $patient->update()) {
                        $message = "Profil mis à jour avec succès";
                    } else {
                        throw new \Exception("Erreur lors de la mise à jour du profil");
                    }
                } else {
                    $error = "Erreur de validation des données";
                }
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        require_once __DIR__ . '/../views/patient/profile.php';
    }
    
    public function appointments() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /SmartCabinet/auth/login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $rendezVous = RendezVous::findAllByPatient($userId);
        
        require_once __DIR__ . '/../views/patient/dashboard.php';
    }
}
