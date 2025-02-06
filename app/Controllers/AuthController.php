<?php

namespace App\Controllers;

use App\Models\User;

class AuthController extends Controller
{
    public function loginForm()
    {
        $this->render('auth/login');
    }

    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
            $this->redirect('/');
        } else {
            $this->render('auth/login', ['error' => 'Email ou mot de passe incorrect']);
        }
    }

    public function registerForm()
    {
        $this->render('auth/register');
    }

    public function register()
    {
        $user = new User();
        $user->nom = $_POST['nom'];
        $user->prenom = $_POST['prenom'];
        $user->email = $_POST['email'];
        $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user->role = 'patient'; // Par défaut, on enregistre comme patient

        if ($user->save()) {
            // Envoyer un email de vérification
            $token = bin2hex(random_bytes(50));
            $user->verification_token = $token;
            $user->save();

            // Envoyer l'email (à implémenter)
            // sendVerificationEmail($user->email, $token);

            $this->render('auth/register_success');
        } else {
            $this->render('auth/register', ['error' => 'Erreur lors de l\'inscription']);
        }
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/login');
    }

    public function verifyEmail($token)
    {
        $user = User::findByVerificationToken($token);
        if ($user) {
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->verification_token = null;
            $user->save();
            $this->render('auth/email_verified');
        } else {
            $this->render('auth/invalid_token');
        }
    }

    public function forgotPasswordForm()
    {
        $this->render('auth/forgot_password');
    }

    public function forgotPassword()
    {
        $email = $_POST['email'];
        $user = User::findByEmail($email);

        if ($user) {
            $token = bin2hex(random_bytes(50));
            $user->reset_token = $token;
            $user->reset_token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $user->save();

            // Envoyer l'email de réinitialisation (à implémenter)
            // sendResetPasswordEmail($user->email, $token);

            $this->render('auth/reset_password_sent');
        } else {
            $this->render('auth/forgot_password', ['error' => 'Aucun compte trouvé avec cet email']);
        }
    }

    public function resetPasswordForm($token)
    {
        $user = User::findByResetToken($token);
        if ($user && strtotime($user->reset_token_expiry) > time()) {
            $this->render('auth/reset_password', ['token' => $token]);
        } else {
            $this->render('auth/invalid_token');
        }
    }

    public function resetPassword()
    {
        $token = $_POST['token'];
        $password = $_POST['password'];

        $user = User::findByResetToken($token);
        if ($user && strtotime($user->reset_token_expiry) > time()) {
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            $user->reset_token = null;
            $user->reset_token_expiry = null;
            $user->save();
            $this->render('auth/password_reset_success');
        } else {
            $this->render('auth/invalid_token');
        }
    }
}