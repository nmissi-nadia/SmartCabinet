<?php
namespace App\Controllers;

use App\Core\Database;

class MedecinController {
    private Database $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function dashboard() {
        // Vérifier si l'utilisateur est connecté et est un médecin
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Médecin') {
            header('Location: /SmartCabinet/auth/login');
            exit;
        }

        $id_utilisateur = $_SESSION['user_id'];

        try {
            // Récupérer les informations du médecin
            $stmt = $this->db->prepare("
                SELECT u.*, im.*, r.role_name
                FROM utilisateurs u
                JOIN infos_medecins im ON u.id_utilisateur = im.id_utilisateur
                JOIN roles r ON u.id_role = r.id_role
                WHERE u.id_utilisateur = ?
            ");
            $stmt->execute([$id_utilisateur]);
            $medecin = $stmt->fetch();

            // Récupérer les rendez-vous du jour
            $stmt = $this->db->prepare("
                SELECT rdv.*, u.nom as patient_nom, u.prenom as patient_prenom
                FROM rendez_vous rdv
                JOIN utilisateurs u ON rdv.id_patient = u.id_utilisateur
                JOIN infos_medecins im ON rdv.id_medecin = im.id_medecin
                WHERE im.id_utilisateur = ?
                AND DATE(rdv.date_rdv) = CURRENT_DATE
                ORDER BY rdv.date_rdv ASC
            ");
            $stmt->execute([$id_utilisateur]);
            $rendez_vous_jour = $stmt->fetchAll();

            // Récupérer les prochains rendez-vous
            $stmt = $this->db->prepare("
                SELECT rdv.*, u.nom as patient_nom, u.prenom as patient_prenom
                FROM rendez_vous rdv
                JOIN utilisateurs u ON rdv.id_patient = u.id_utilisateur
                JOIN infos_medecins im ON rdv.id_medecin = im.id_medecin
                WHERE im.id_utilisateur = ?
                AND DATE(rdv.date_rdv) > CURRENT_DATE
                ORDER BY rdv.date_rdv ASC
                LIMIT 5
            ");
            $stmt->execute([$id_utilisateur]);
            $prochains_rdv = $stmt->fetchAll();

            require_once __DIR__ . '/../views/medecin/dashboard.php';
        } catch (\Exception $e) {
            // Gérer l'erreur
            echo "Une erreur est survenue : " . $e->getMessage();
        }
    }

    public function updateDisponibilite() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_utilisateur = $_SESSION['user_id'];
            $disponibilite = json_encode($_POST['disponibilite']);

            try {
                $stmt = $this->db->prepare("
                    UPDATE infos_medecins 
                    SET disponibilite = ? 
                    WHERE id_utilisateur = ?
                ");
                $stmt->execute([$disponibilite, $id_utilisateur]);
                
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
            } catch (\Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['error' => $e->getMessage()]);
            }
        }
    }
}
