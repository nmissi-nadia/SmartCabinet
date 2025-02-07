<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\Patient;
use App\Models\Appointment;

class PatientController {
    private Database $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function dashboard() {
        // Vérifier si l'utilisateur est connecté et est un patient
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Patient') {
            header('Location: /SmartCabinet/auth/login');
            exit;
        }

        $id_utilisateur = $_SESSION['user_id'];

        try {
            // Récupérer les informations du patient
            $stmt = $this->db->prepare("
                SELECT u.*, ip.*, r.role_name
                FROM utilisateurs u
                JOIN infos_patients ip ON u.id_utilisateur = ip.id_utilisateur
                JOIN roles r ON u.id_role = r.id_role
                WHERE u.id_utilisateur = ?
            ");
            $stmt->execute([$id_utilisateur]);
            $patient = $stmt->fetch();

            // Récupérer les prochains rendez-vous
            $stmt = $this->db->prepare("
                SELECT rdv.*, 
                       m.nom as medecin_nom, m.prenom as medecin_prenom,
                       im.specialite as specialite
                FROM rendez_vous rdv
                JOIN infos_medecins im ON rdv.id_medecin = im.id_medecin
                JOIN utilisateurs m ON im.id_utilisateur = m.id_utilisateur
                WHERE rdv.id_patient = ?
                AND rdv.date_rdv >= CURRENT_DATE
                ORDER BY rdv.date_rdv ASC
            ");
            $stmt->execute([$id_utilisateur]);
            $prochains_rdv = $stmt->fetchAll();

            // Récupérer l'historique des rendez-vous
            $stmt = $this->db->prepare("
                SELECT rdv.*, 
                       m.nom as medecin_nom, m.prenom as medecin_prenom,
                       im.specialite as specialite
                FROM rendez_vous rdv
                JOIN infos_medecins im ON rdv.id_medecin = im.id_medecin
                JOIN utilisateurs m ON im.id_utilisateur = m.id_utilisateur
                WHERE rdv.id_patient = ?
                AND rdv.date_rdv < CURRENT_DATE
                ORDER BY rdv.date_rdv DESC
                LIMIT 5
            ");
            $stmt->execute([$id_utilisateur]);
            $historique_rdv = $stmt->fetchAll();

            // Récupérer la liste des médecins pour la prise de rendez-vous
            $stmt = $this->db->prepare("
                SELECT m.id_utilisateur, m.nom, m.prenom, im.specialite, im.adresse_cabinet
                FROM utilisateurs m
                JOIN infos_medecins im ON m.id_utilisateur = im.id_utilisateur
                JOIN roles r ON m.id_role = r.id_role
                WHERE r.role_name = 'Médecin'
                ORDER BY m.nom, m.prenom
            ");
            $stmt->execute();
            $medecins = $stmt->fetchAll();

            require_once __DIR__ . '/../views/patient/dashboard.php';
        } catch (\Exception $e) {
            // Gérer l'erreur
            echo "Une erreur est survenue : " . $e->getMessage();
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
                
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
            } catch (\Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['error' => $e->getMessage()]);
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
                
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
            } catch (\Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['error' => $e->getMessage()]);
            }
        }
    }

    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /SmartCabinet/auth/login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $patient = Patient::findOne(['id_utilisateur' => $userId]);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patient->loadData($_POST);
            if ($patient->update()) {
                $_SESSION['flash'] = 'Profil mis à jour avec succès';
                header('Location: /patient/profile');
                exit;
            }
        }
        
        require_once __DIR__ . '/../views/patient/profile.php';
    }
    
    public function appointments() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /SmartCabinet/auth/login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $appointments = Appointment::findAllByPatient($userId);
        
        require_once __DIR__ . '/../views/patient/appointments.php';
    }
}
