<?php
namespace App\Controllers;

use App\Core\Application;
use PDO;

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (!$email || !$password) {
                header('Location: ' . Application::$app->getBaseUrl() . '/auth/login');
                exit;
            }

            try {
                $db = Application::$app->getDatabase();
                
                $stmt = $db->prepare("
                    SELECT u.*, r.role_name 
                    FROM utilisateurs u
                    JOIN roles r ON u.id_role = r.id_role
                    WHERE u.email = ?
                ");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['mot_de_passe'])) {
                    $_SESSION['user_id'] = $user['id_utilisateur'];
                    $_SESSION['user_role'] = $user['role_name'];
                    $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
                    $baseUrl = Application::$app->getBaseUrl();
                    if ($user['role_name'] === 'Médecin') {
                        header('Location: ' . $baseUrl . '/medecin/dashboard');
                    } elseif ($user['role_name'] === 'Patient') {
                        header('Location: ' . $baseUrl . '/patient/dashboard');
                       
                    }
                    exit;
                }

                header('Location: ' . Application::$app->getBaseUrl() . '/auth/login');
                exit;
            } catch (\Exception $e) {
                header('Location: ' . Application::$app->getBaseUrl() . '/auth/login');
                exit;
            }
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    // Méthode pour l'inscription
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
            $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $id_role = $_POST['role'] ?? ''; 
            
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
                    $db = Application::$app->getDatabase();
                    
                    // Vérifier si l'email existe déjà
                    $stmt = $db->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ?");
                    $stmt->execute([$email]);
                    if ($stmt->fetchColumn() > 0) {
                        header('Location: ' . Application::$app->getBaseUrl() . '/auth/register');
                        exit;
                    } else {
                        // Commencer une transaction
                        $db->beginTransaction();
                        
                        try {
                            // Insertion de l'utilisateur
                            $stmt = $db->prepare("
                                INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, id_role) 
                                VALUES (?, ?, ?, ?, ?)
                            ");
                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                            $stmt->execute([$nom, $prenom, $email, $hashed_password, $id_role]);
                            
                            $user_id = $db->lastInsertId();
                            
                            // Traitement spécifique selon le rôle
                            if ($id_role === '2') { // Patient
                                $numero_secu = $_POST['numero_securite_sociale'] ?? '';
                                $stmt = $db->prepare("
                                    INSERT INTO infos_patients (id_utilisateur, numero_secu) 
                                    VALUES (?, ?)
                                ");
                                $stmt->execute([$user_id, $numero_secu]);
                            } elseif ($id_role === '1') { // Médecin
                                $specialite = $_POST['specialite'] ?? '';
                                $numero_rpps = $_POST['numero_rpps'] ?? '';
                                $adresse = $_POST['adresse_cabinet'] ?? '';
                                $numero_telephone = $_POST['numero_telephone'] ?? '';
                                
                                $stmt = $db->prepare("
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
                            
                            $db->commit();
                            
                            header('Location: ' . Application::$app->getBaseUrl() . '/auth/login');
                            exit;
                        } catch (\Exception $e) {
                            $db->rollBack();
                            throw $e;
                        }
                    }
                } catch (\Exception $e) {
                    header('Location: ' . Application::$app->getBaseUrl() . '/auth/register');
                    exit;
                }
            } else {
                header('Location: ' . Application::$app->getBaseUrl() . '/auth/register');
                exit;
            }
        }
        
        // Afficher la vue
        require_once __DIR__ . '/../views/auth/register.php';
    }
    
    // Méthode pour la déconnexion
    public function logout() {
        session_destroy();
        header('Location: ' . Application::$app->getBaseUrl() . '/auth/login');
        exit;
    }
}
