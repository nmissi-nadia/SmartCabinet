<?php
namespace App\Controllers;

use App\Core\Database;

class AuthController {
    private Database $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Méthode pour la connexion
    public function login() {
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Recherche de l'utilisateur avec son rôle
            $stmt = $this->db->prepare("
                SELECT u.*, r.role_name 
                FROM utilisateurs u 
                JOIN roles r ON u.id_role = r.id_role 
                WHERE u.email = ?
            ");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['mot_de_passe'])) {
                // Démarrer la session si pas déjà démarrée
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                
                $_SESSION['user_id'] = $user['id_utilisateur'];
                $_SESSION['user_role'] = $user['role_name'];
                $_SESSION['success'] = 'Connexion réussie';
                
                // Redirection selon le rôle
                if ($user['role_name'] === 'Médecin') {
                    header('Location: /SmartCabinet/medecin/dashboard');
                } else {
                    header('Location: /SmartCabinet/patient/dashboard');
                }
                exit;
            } else {
                $error = 'Email ou mot de passe incorrect';
            }
        }
        
        // Afficher la vue
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    // Méthode pour l'inscription
    public function register() {
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $id_role = $_POST['role'] ?? ''; // 1 pour médecin, 2 pour patient
            
            // Validation simple
            $errors = [];
            if (empty($nom)) $errors[] = "Le nom est requis";
            if (empty($prenom)) $errors[] = "Le prénom est requis";
            if (empty($email)) $errors[] = "L'email est requis";
            if (empty($password)) $errors[] = "Le mot de passe est requis";
            if (empty($id_role)) $errors[] = "Le rôle est requis";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "L'email n'est pas valide";
            if (strlen($password) < 6) $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
            
            // Si pas d'erreurs, on continue
            if (empty($errors)) {
                try {
                    // Vérifier si l'email existe déjà
                    $stmt = $this->db->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ?");
                    $stmt->execute([$email]);
                    if ($stmt->fetchColumn() > 0) {
                        $error = "Cet email est déjà utilisé";
                    } else {
                        // Commencer une transaction
                        $this->db->beginTransaction();
                        
                        try {
                            // Insertion de l'utilisateur
                            $stmt = $this->db->prepare("
                                INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, id_role) 
                                VALUES (?, ?, ?, ?, ?)
                            ");
                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                            $stmt->execute([$nom, $prenom, $email, $hashed_password, $id_role]);
                            
                            $user_id = $this->db->lastInsertId();
                            
                            // Traitement spécifique selon le rôle
                            if ($id_role === '2') { // Patient
                                $numero_secu = $_POST['numero_securite_sociale'] ?? '';
                                $stmt = $this->db->prepare("
                                    INSERT INTO infos_patients (id_utilisateur, numero_secu) 
                                    VALUES (?, ?)
                                ");
                                $stmt->execute([$user_id, $numero_secu]);
                            } elseif ($id_role === '1') { // Médecin
                                $specialite = $_POST['specialite'] ?? '';
                                $numero_rpps = $_POST['numero_rpps'] ?? '';
                                $adresse = $_POST['adresse_cabinet'] ?? '';
                                $numero_telephone = $_POST['numero_telephone'] ?? '';
                                
                                $stmt = $this->db->prepare("
                                    INSERT INTO infos_medecins (
                                        id_utilisateur, 
                                        specialite, 
                                        numero_telephone,
                                        adresse_cabinet
                                    ) VALUES (?, ?, ?, ?)
                                ");
                                $stmt->execute([
                                    $user_id, 
                                    $specialite, 
                                    $numero_telephone,
                                    $adresse
                                ]);
                            }
                            
                            $this->db->commit();
                            
                            $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                            header('Location: /SmartCabinet/auth/login');
                            exit;
                        } catch (\Exception $e) {
                            $this->db->rollBack();
                            throw $e;
                        }
                    }
                } catch (\Exception $e) {
                    $error = "Erreur lors de l'inscription : " . $e->getMessage();
                }
            } else {
                $error = implode("<br>", $errors);
            }
        }
        
        // Afficher la vue
        require_once __DIR__ . '/../views/auth/register.php';
    }
    
    // Méthode pour la déconnexion
    public function logout() {
        session_start();
        session_destroy();
        header('Location: /SmartCabinet/auth/login');
        exit;
    }
}
