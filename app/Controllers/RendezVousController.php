<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\RendezVous;
use App\Core\Application;

class RendezVousController {
    private Database $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function showCreateForm() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /SmartCabinet/auth/login');
            exit;
        }

        try {
            // Récupérer la liste des médecins
            $stmt = $this->db->prepare("
                SELECT m.id_utilisateur, m.nom, m.prenom, im.id_medecin, im.specialite
                FROM utilisateurs m
                JOIN infos_medecins im ON m.id_utilisateur = im.id_utilisateur
                JOIN roles r ON m.id_role = r.id_role
                WHERE r.role_name = 'Médecin'
                ORDER BY m.nom, m.prenom
            ");
            $stmt->execute();
            $medecins = $stmt->fetchAll();

            // Afficher le formulaire avec la liste des médecins
            require_once __DIR__ . '/../views/patient/create_appointment.php';
        } catch (\Exception $e) {
            $_SESSION['error'] = "Une erreur est survenue lors du chargement du formulaire.";
            header('Location: /SmartCabinet/patient/dashboard');
            exit;
        }
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /SmartCabinet/patient/dashboard');
            exit;
        }
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /SmartCabinet/auth/login');
            exit;
        }

        $id_patient = $_SESSION['user_id'];
        $id_medecin = $_POST['id_medecin'] ?? null;
        $date_rdv = $_POST['date_rdv'] ?? null;
        $commentaire = $_POST['commentaire'] ?? '';

        if (!$id_medecin || !$date_rdv) {
            $_SESSION['error'] = "Tous les champs requis doivent être remplis.";
            header('Location: /SmartCabinet/patient/dashboard');
            exit;
        }

        try {
            // Vérifier la disponibilité du médecin
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM rendez_vous 
                WHERE id_medecin = ? 
                AND date_rdv = ? 
                AND statut != 'Annulé'
            ");
            $stmt->execute([$id_medecin, $date_rdv]);
            $result = $stmt->fetch();

            if ($result['count'] > 0) {
                $_SESSION['error'] = "Ce créneau n'est pas disponible.";
                header('Location: /SmartCabinet/patient/dashboard');
                exit;
            }

            // Créer le rendez-vous
            $stmt = $this->db->prepare("
                INSERT INTO rendez_vous (id_patient, id_medecin, date_rdv, statut, commentaire)
                VALUES (?, ?, ?, 'En attente', ?)
            ");
            $stmt->execute([$id_patient, $id_medecin, $date_rdv, $commentaire]);

            $_SESSION['success'] = "Votre rendez-vous a été créé avec succès.";
            header('Location: /SmartCabinet/patient/dashboard');
            exit;

        } catch (\Exception $e) {
            $_SESSION['error'] = "Une erreur est survenue lors de la création du rendez-vous.";
            header('Location: /SmartCabinet/patient/dashboard');
            exit;
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /SmartCabinet/patient/dashboard');
            exit;
        }

        $id_rdv = $_POST['id_rdv'] ?? null;
        $statut = $_POST['statut'] ?? null;
        $commentaire = $_POST['commentaire'] ?? null;

        if (!$id_rdv || !$statut) {
            $_SESSION['error'] = "Paramètres invalides.";
            header('Location: /SmartCabinet/patient/dashboard');
            exit;
        }

        try {
            $stmt = $this->db->prepare("
                UPDATE rendez_vous 
                SET statut = ?, 
                    commentaire = COALESCE(?, commentaire)
                WHERE id_rdv = ?
            ");
            $stmt->execute([$statut, $commentaire, $id_rdv]);

            $_SESSION['success'] = "Le rendez-vous a été mis à jour avec succès.";
        } catch (\Exception $e) {
            $_SESSION['error'] = "Une erreur est survenue lors de la mise à jour du rendez-vous.";
        }

        header('Location: /SmartCabinet/patient/dashboard');
        exit;
    }

    public function cancel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /SmartCabinet/patient/dashboard');
            exit;
        }

        $id_rdv = $_POST['id_rdv'] ?? null;

        if (!$id_rdv) {
            $_SESSION['error'] = "ID de rendez-vous manquant.";
            header('Location: /SmartCabinet/patient/dashboard');
            exit;
        }

        try {
            // Vérifier si le rendez-vous appartient bien au patient connecté
            $stmt = $this->db->prepare("
                SELECT id_patient 
                FROM rendez_vous 
                WHERE id_rdv = ?
            ");
            $stmt->execute([$id_rdv]);
            $rdv = $stmt->fetch();

            if (!$rdv || $rdv['id_patient'] != $_SESSION['user_id']) {
                $_SESSION['error'] = "Vous n'êtes pas autorisé à annuler ce rendez-vous.";
                header('Location: /SmartCabinet/patient/dashboard');
                exit;
            }

            $stmt = $this->db->prepare("
                UPDATE rendez_vous 
                SET statut = 'Annulé'
                WHERE id_rdv = ?
            ");
            $stmt->execute([$id_rdv]);

            $_SESSION['success'] = "Le rendez-vous a été annulé avec succès.";
        } catch (\Exception $e) {
            $_SESSION['error'] = "Une erreur est survenue lors de l'annulation du rendez-vous.";
        }

        header('Location: /SmartCabinet/patient/dashboard');
        exit;
    }

    public function getDisponibilites() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée']);
            exit;
        }

        $id_medecin = $_GET['id_medecin'] ?? null;
        $date = $_GET['date'] ?? null;

        if (!$id_medecin || !$date) {
            http_response_code(400);
            echo json_encode(['error' => 'Paramètres manquants']);
            exit;
        }

        try {
            // Récupérer les disponibilités du médecin
            $stmt = $this->db->prepare("
                SELECT disponibilite 
                FROM infos_medecins 
                WHERE id_medecin = ?
            ");
            $stmt->execute([$id_medecin]);
            $medecin = $stmt->fetch();

            if (!$medecin) {
                http_response_code(404);
                echo json_encode(['error' => 'Médecin non trouvé']);
                exit;
            }

            // Récupérer les rendez-vous existants pour cette date
            $stmt = $this->db->prepare("
                SELECT date_rdv 
                FROM rendez_vous 
                WHERE id_medecin = ? 
                AND DATE(date_rdv) = ? 
                AND statut != 'Annulé'
            ");
            $stmt->execute([$id_medecin, $date]);
            $rdvs = $stmt->fetchAll();

            // Convertir les rendez-vous en créneaux occupés
            $creneaux_occupes = array_map(function($rdv) {
                return date('H:i', strtotime($rdv['date_rdv']));
            }, $rdvs);

            // Retourner les créneaux disponibles
            echo json_encode([
                'disponibilites' => json_decode($medecin['disponibilite'], true),
                'creneaux_occupes' => $creneaux_occupes
            ]);
            exit;

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Une erreur est survenue']);
            exit;
        }
    }
}
