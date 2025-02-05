<?php

namespace App\Controllers;

use Core\Database;

class AuthController
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function login()
    {
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function authenticate()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            echo "Email ou mot de passe manquant.";
            return;
        }

        $query = "SELECT * FROM users WHERE email = :email";
        $user = $this->db->query($query, ['email' => $email])->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header('Location: /SmartCabinet/public/dashboard');
            exit;
        } else {
            echo "Email ou mot de passe incorrect.";
        }
    }

    public function register()
    {
        require_once __DIR__ . '/../Views/auth/register.php';
    }

    public function store()
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            echo "Tous les champs sont obligatoires.";
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $this->db->query($query, [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
        ]);

        echo "Inscription réussie. <a href='/SmartCabinet/public/login'>Connectez-vous</a>";
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /SmartCabinet/public/login');
        exit;
    }
}
?>