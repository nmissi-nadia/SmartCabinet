<?php
namespace App\Controllers;

use App\Core\Application;
use App\Models\User;
use App\Models\Patient;

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = User::findOne(['email' => $email]);
            
            if ($user && password_verify($password, $user->mot_de_passe)) {
                Application::$app->getSession()->set('user', $user->id_utilisateur);
                Application::$app->getSession()->setFlash('success', 'Connexion réussie');
                
                // Redirection selon le rôle
                if ($user->role === 'medecin') {
                    Application::$app->getRouter()->redirect(Application::$app->getBaseUrl() . '/medecin/dashboard');
                } else {
                    Application::$app->getRouter()->redirect(Application::$app->getBaseUrl() . '/patient/dashboard');
                }
            }
            
            Application::$app->getSession()->setFlash('error', 'Email ou mot de passe incorrect');
        }
        
        return Application::$app->getRouter()->renderView('auth/login', [
            'title' => 'Connexion'
        ]);
    }
    
    public function register() {
        $user = new User();
        $patient = new Patient();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user->loadData($_POST);
            $user->role = 'patient';
            
            // Hash du mot de passe
            $user->mot_de_passe = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            // Validation et sauvegarde de l'utilisateur
            if ($user->validate() && $user->save()) {
                // Création du patient associé
                $patient->id_utilisateur = $user->id_utilisateur;
                $patient->numero_securite_sociale = $_POST['numero_securite_sociale'] ?? '';
                
                if ($patient->validate() && $patient->save()) {
                    Application::$app->getSession()->setFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
                    Application::$app->getRouter()->redirect(Application::$app->getBaseUrl() . '/login');
                    return;
                }
            }
            
            Application::$app->getSession()->setFlash('error', 'Erreur lors de l\'inscription. Veuillez vérifier vos informations.');
        }
        
        return Application::$app->getRouter()->renderView('auth/register', [
            'title' => 'Inscription',
            'user' => $user,
            'patient' => $patient
        ]);
    }
    
    public function logout() {
        Application::$app->getSession()->remove('user');
        Application::$app->getSession()->setFlash('success', 'Vous avez été déconnecté');
        Application::$app->getRouter()->redirect(Application::$app->getBaseUrl() . '/');
    }
    
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $user = User::findOne(['email' => $email]);
            
            if ($user) {
                // Génération d'un token de réinitialisation
                $token = bin2hex(random_bytes(32));
                $user->reset_token = $token;
                $user->reset_token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                if ($user->save()) {
                    // TODO: Envoyer l'email avec le lien de réinitialisation
                    Application::$app->getSession()->setFlash('success', 'Un email de réinitialisation vous a été envoyé.');
                    Application::$app->getRouter()->redirect(Application::$app->getBaseUrl() . '/login');
                    return;
                }
            }
            
            Application::$app->getSession()->setFlash('error', 'Aucun compte trouvé avec cet email.');
        }
        
        return Application::$app->getRouter()->renderView('auth/forgot-password', [
            'title' => 'Mot de passe oublié'
        ]);
    }
    
    public function resetPassword($token) {
        $user = User::findOne(['reset_token' => $token]);
        
        if (!$user || strtotime($user->reset_token_expiry) < time()) {
            Application::$app->getSession()->setFlash('error', 'Le lien de réinitialisation est invalide ou a expiré.');
            Application::$app->getRouter()->redirect(Application::$app->getBaseUrl() . '/login');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            
            if ($password === $passwordConfirm) {
                $user->mot_de_passe = password_hash($password, PASSWORD_DEFAULT);
                $user->reset_token = null;
                $user->reset_token_expiry = null;
                
                if ($user->save()) {
                    Application::$app->getSession()->setFlash('success', 'Votre mot de passe a été réinitialisé. Vous pouvez maintenant vous connecter.');
                    Application::$app->getRouter()->redirect(Application::$app->getBaseUrl() . '/login');
                    return;
                }
            }
            
            Application::$app->getSession()->setFlash('error', 'Les mots de passe ne correspondent pas.');
        }
        
        return Application::$app->getRouter()->renderView('auth/reset-password', [
            'title' => 'Réinitialisation du mot de passe',
            'token' => $token
        ]);
    }
}
