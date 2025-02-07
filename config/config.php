<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'cabinet_medical');
define('DB_USER', 'postgres');
define('DB_PASS', '5876');
define('DB_PORT', '5432');

// Configuration de l'application
define('BASE_URL', '/SmartCabinet');
define('ROOT_PATH', dirname(__DIR__));

// Configuration des sessions
session_start();

// Fonction de connexion à la base de données
function getConnection() {
    try {
        $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch(PDOException $e) {
        die('Erreur de connexion : ' . $e->getMessage());
    }
}

// Fonctions utilitaires
function redirect($path) {
    header("Location: " . BASE_URL . $path);
    exit();
}

function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getCurrentUser() {
    if (isLoggedIn()) {
        $db = getConnection();
        $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = $1");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
    return null;
}

function isPatient() {
    $user = getCurrentUser();
    return $user && $user['role'] === 'patient';
}

function isMedecin() {
    $user = getCurrentUser();
    return $user && $user['role'] === 'medecin';
}

function requireAuth() {
    if (!isLoggedIn()) {
        setFlash('error', 'Vous devez être connecté pour accéder à cette page');
        redirect('/auth/login');
    }
}

function requirePatient() {
    requireAuth();
    if (!isPatient()) {
        setFlash('error', 'Accès réservé aux patients');
        redirect('/');
    }
}

function requireMedecin() {
    requireAuth();
    if (!isMedecin()) {
        setFlash('error', 'Accès réservé aux médecins');
        redirect('/');
    }
}

// Fonction pour nettoyer les entrées utilisateur
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
