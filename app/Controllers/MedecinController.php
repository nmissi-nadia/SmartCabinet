<?php
namespace App\Controllers;

use App\Core\Application;
use App\Models\Medecin;
use App\Models\User;
use App\Models\RendezVous;
use App\Core\Database;

class MedecinController {
    private Database $db;

    public function __construct() {
        $this->db = new Database();
        
        // Vérifier l'authentification pour toutes les méthodes sauf login
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Médecin') {
            header('Location: ' . Application::$app->getBaseUrl() . '/auth/login');
            exit;
        }
    }

    public function dashboard() {
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

            // Récupérer les statistiques
            $stats = $this->getStatistics($id_utilisateur);

            require_once __DIR__ . '/../views/medecin/dashboard.php';
        } catch (\Exception $e) {
            header('Location: ' . $_SERVER['BASE_URL'] . '/auth/login');
            exit;
        }
    }

    private function getStatistics($id_utilisateur) {
        try {
            $stats = [];
            
            // 1. Nombre total de patients
            $stmt = $this->db->prepare("
                SELECT COUNT(DISTINCT rdv.id_patient) as total_patients
                FROM rendez_vous rdv
                JOIN infos_medecins im ON rdv.id_medecin = im.id_medecin
                WHERE im.id_utilisateur = ?
            ");
            $stmt->execute([$id_utilisateur]);
            $stats['total_patients'] = $stmt->fetchColumn();

            // 2. Nombre de consultations ce mois
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as consultations_mois
                FROM rendez_vous rdv
                JOIN infos_medecins im ON rdv.id_medecin = im.id_medecin
                WHERE im.id_utilisateur = ?
                AND MONTH(rdv.date_rdv) = MONTH(CURRENT_DATE)
                AND YEAR(rdv.date_rdv) = YEAR(CURRENT_DATE)
                AND rdv.statut = 'Terminé'
            ");
            $stmt->execute([$id_utilisateur]);
            $stats['consultations_mois'] = $stmt->fetchColumn();

            // 3. Nombre de nouveaux patients ce mois
            $stmt = $this->db->prepare("
                SELECT COUNT(DISTINCT rdv.id_patient) as nouveaux_patients
                FROM rendez_vous rdv
                JOIN infos_medecins im ON rdv.id_medecin = im.id_medecin
                WHERE im.id_utilisateur = ?
                AND MONTH(rdv.date_rdv) = MONTH(CURRENT_DATE)
                AND YEAR(rdv.date_rdv) = YEAR(CURRENT_DATE)
                AND rdv.id_patient NOT IN (
                    SELECT DISTINCT r2.id_patient
                    FROM rendez_vous r2
                    WHERE r2.id_medecin = rdv.id_medecin
                    AND (
                        DATE(r2.date_rdv) < DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
                    )
                )
            ");
            $stmt->execute([$id_utilisateur]);
            $stats['nouveaux_patients'] = $stmt->fetchColumn();

            // 4. Taux de consultation (rendez-vous honorés / total des rendez-vous) ce mois
            $stmt = $this->db->prepare("
                SELECT 
                    COUNT(CASE WHEN statut = 'Terminé' THEN 1 END) as rdv_honores,
                    COUNT(*) as total_rdv
                FROM rendez_vous rdv
                JOIN infos_medecins im ON rdv.id_medecin = im.id_medecin
                WHERE im.id_utilisateur = ?
                AND MONTH(rdv.date_rdv) = MONTH(CURRENT_DATE)
                AND YEAR(rdv.date_rdv) = YEAR(CURRENT_DATE)
                AND rdv.date_rdv < CURRENT_TIMESTAMP
            ");
            $stmt->execute([$id_utilisateur]);
            $result = $stmt->fetch();
            $stats['taux_consultation'] = $result['total_rdv'] > 0 
                ? round(($result['rdv_honores'] / $result['total_rdv']) * 100, 2)
                : 0;

            // 5. Répartition des consultations par jour de la semaine
            $stmt = $this->db->prepare("
                SELECT 
                    DAYNAME(date_rdv) as jour,
                    COUNT(*) as nombre
                FROM rendez_vous rdv
                JOIN infos_medecins im ON rdv.id_medecin = im.id_medecin
                WHERE im.id_utilisateur = ?
                AND MONTH(rdv.date_rdv) = MONTH(CURRENT_DATE)
                AND YEAR(rdv.date_rdv) = YEAR(CURRENT_DATE)
                AND rdv.statut = 'Terminé'
                GROUP BY DAYNAME(date_rdv)
                ORDER BY DAYOFWEEK(date_rdv)
            ");
            $stmt->execute([$id_utilisateur]);
            $stats['repartition_jours'] = $stmt->fetchAll();

            return $stats;

        } catch (\Exception $e) {
            // En cas d'erreur, retourner un tableau vide
            return [
                'total_patients' => 0,
                'consultations_mois' => 0,
                'nouveaux_patients' => 0,
                'taux_consultation' => 0,
                'repartition_jours' => []
            ];
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

    public function confirmAppointment($id) {
        try {
            $rdv = RendezVous::findOne(['id_rdv' => $id]);
            
            if ($rdv && $rdv->id_medecin == $_SESSION['user_id']) {
                if (RendezVous::confirmerRdv($id)) {
                    header('Location: ' . Application::$app->getBaseUrl() . '/medecin/dashboard');
                    exit;
                } else {
                    throw new \Exception("Erreur lors de la confirmation du rendez-vous");
                }
            } else {
                throw new \Exception("Rendez-vous non trouvé ou non autorisé");
            }
        } catch (\Exception $e) {
            header('Location: ' . Application::$app->getBaseUrl() . '/medecin/dashboard?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function cancelAppointment($id) {
        try {
            $rdv = RendezVous::findOne(['id_rdv' => $id]);
            
            if ($rdv && $rdv->id_medecin == $_SESSION['user_id']) {
                if (RendezVous::annulerRdv($id)) {
                    header('Location: ' . Application::$app->getBaseUrl() . '/medecin/dashboard');
                    exit;
                } else {
                    throw new \Exception("Erreur lors de l'annulation du rendez-vous");
                }
            } else {
                throw new \Exception("Rendez-vous non trouvé ou non autorisé");
            }
        } catch (\Exception $e) {
            header('Location: ' . Application::$app->getBaseUrl() . '/medecin/dashboard?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function listePatients() {
        try {
            $id_utilisateur = $_SESSION['user_id'];
            
            // Récupérer la liste des patients qui ont eu des rendez-vous avec ce médecin
            $stmt = $this->db->prepare("
                SELECT DISTINCT u.*, ip.numero_secu
                FROM utilisateurs u
                JOIN infos_patients ip ON u.id_utilisateur = ip.id_utilisateur
                JOIN rendez_vous rdv ON u.id_utilisateur = rdv.id_patient
                JOIN infos_medecins im ON rdv.id_medecin = im.id_medecin
                WHERE im.id_utilisateur = ?
                ORDER BY u.nom, u.prenom
            ");
            $stmt->execute([$id_utilisateur]);
            $patients = $stmt->fetchAll();

            require_once __DIR__ . '/../views/medecin/liste_patients.php';
        } catch (\Exception $e) {
            header('Location: ' . $_SERVER['BASE_URL'] . '/auth/login');
            exit;
        }
    }

    public function detailPatient($id) {
        try {
            $id_utilisateur = $_SESSION['user_id'];
            
            // Récupérer les informations du patient
            $stmt = $this->db->prepare("
                SELECT u.*, ip.numero_secu
                FROM utilisateurs u
                JOIN infos_patients ip ON u.id_utilisateur = ip.id_utilisateur
                WHERE u.id_utilisateur = ?
            ");
            $stmt->execute([$id]);
            $patient = $stmt->fetch();

            // Récupérer l'historique des rendez-vous avec ce patient
            $stmt = $this->db->prepare("
                SELECT rdv.*
                FROM rendez_vous rdv
                JOIN infos_medecins im ON rdv.id_medecin = im.id_medecin
                WHERE rdv.id_patient = ? AND im.id_utilisateur = ?
                ORDER BY rdv.date_rdv DESC
            ");
            $stmt->execute([$id, $id_utilisateur]);
            $rendez_vous = $stmt->fetchAll();

            require_once __DIR__ . '/../views/medecin/detail_patient.php';
        } catch (\Exception $e) {
            header('Location: ' . $_SERVER['BASE_URL'] . '/auth/login');
            exit;
        }
    }

    public function profil() {
        $baseUrl = Application::$app->getBaseUrl();
        $message = '';
        $error = '';

        try {
            // Récupérer les informations de l'utilisateur et du médecin
            $stmt = $this->db->prepare("
                SELECT u.*, im.*
                FROM utilisateurs u
                JOIN infos_medecins im ON u.id_utilisateur = im.id_utilisateur
                WHERE u.id_utilisateur = ?
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $medecin = $stmt->fetch();

            if (!$medecin) {
                throw new \Exception("Utilisateur ou informations médecin non trouvés");
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Mettre à jour les informations de l'utilisateur
                $stmt = $this->db->prepare("
                    UPDATE utilisateurs 
                    SET nom = ?, prenom = ?, email = ?
                    WHERE id_utilisateur = ?
                ");
                $stmt->execute([
                    $_POST['nom'],
                    $_POST['prenom'],
                    $_POST['email'],
                    $_SESSION['user_id']
                ]);

                // Mettre à jour les informations du médecin
                $stmt = $this->db->prepare("
                    UPDATE infos_medecins 
                    SET specialite = ?, 
                        numero_telephone = ?,
                        adresse_cabinet = ?
                    WHERE id_utilisateur = ?
                ");
                $stmt->execute([
                    $_POST['specialite'],
                    $_POST['numero_telephone'],
                    $_POST['adresse_cabinet'],
                    $_SESSION['user_id']
                ]);

                $message = "Profil mis à jour avec succès";
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        require_once __DIR__ . '/../views/medecin/profil.php';
    }

    public function updateProfil() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_utilisateur = $_SESSION['user_id'];
            
            try {
                $this->db->beginTransaction();

                // Mise à jour des informations de base
                $stmt = $this->db->prepare("
                    UPDATE utilisateurs 
                    SET nom = ?, prenom = ?, email = ?
                    WHERE id_utilisateur = ?
                ");
                $stmt->execute([
                    $_POST['nom'],
                    $_POST['prenom'],
                    $_POST['email'],
                    $id_utilisateur
                ]);

                // Mise à jour des informations spécifiques au médecin
                $stmt = $this->db->prepare("
                    UPDATE infos_medecins 
                    SET specialite = ?, 
                        numero_telephone = ?,
                        adresse_cabinet = ?
                    WHERE id_utilisateur = ?
                ");
                $stmt->execute([
                    $_POST['specialite'],
                    $_POST['numero_telephone'],
                    $_POST['adresse_cabinet'],
                    $id_utilisateur
                ]);

                $this->db->commit();
                
                header('Location: ' . $_SERVER['BASE_URL'] . '/medecin/profil');
                exit;
            } catch (\Exception $e) {
                $this->db->rollBack();
                header('Location: ' . $_SERVER['BASE_URL'] . '/medecin/profil');
                exit;
            }
        }
    }
}
