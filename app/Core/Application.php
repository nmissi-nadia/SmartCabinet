<?php
namespace App\Core;

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\PatientController;
use App\Controllers\RendezVousController;

class Application {
    public static Application $app;
    public static string $ROOT_DIR;
    public Router $router;
    public Session $session;
    public Database $database;
    public string $baseUrl = '/livecodingphpmvc';
    
    public function __construct() {
        self::$ROOT_DIR = dirname(__DIR__);
        self::$app = $this;
        $this->session = new Session();
        $this->router = new Router();
        $this->database = new Database();
        
        // Routes principales
        $this->router->get($this->baseUrl . '/', [HomeController::class, 'index']);
        
        // Routes d'authentification
        $this->router->get($this->baseUrl . '/auth/login', [AuthController::class, 'login']);
        $this->router->post($this->baseUrl . '/auth/login', [AuthController::class, 'login']);
        $this->router->get($this->baseUrl . '/auth/register', [AuthController::class, 'register']);
        $this->router->post($this->baseUrl . '/auth/register', [AuthController::class, 'register']);
        $this->router->get($this->baseUrl . '/auth/logout', [AuthController::class, 'logout']);
        
        // Routes pour les rendez-vous
        $this->router->get($this->baseUrl . '/rendez-vous/nouveau', [RendezVousController::class, 'nouveauForm']);
        $this->router->post($this->baseUrl . '/rendez-vous/nouveau', [RendezVousController::class, 'nouveau']);
        $this->router->get($this->baseUrl . '/rendez-vous/mes-rendez-vous', [RendezVousController::class, 'mesRendezVous']);
        
        // Routes espace patient
        $this->router->get($this->baseUrl . '/patient/dashboard', [PatientController::class, 'dashboard']);
        $this->router->get($this->baseUrl . '/patient/profile', [PatientController::class, 'profile']);
        $this->router->post($this->baseUrl . '/patient/profile', [PatientController::class, 'profile']);
        $this->router->get($this->baseUrl . '/patient/appointments', [PatientController::class, 'appointments']);
    }
    
    public function run() {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            echo $this->router->renderView('error', [
                'exception' => $e
            ]);
        }
    }
    
    public function getRouter(): Router {
        return $this->router;
    }
    
    public function getSession(): Session {
        return $this->session;
    }
    
    public function getDatabase(): Database {
        return $this->database;
    }
    
    public function getBaseUrl(): string {
        return $this->baseUrl;
    }
    
    public static function isGuest(): bool {
        $user = self::$app->session->get('user');
        return $user === null;
    }
    
    public static function isPatient(): bool {
        $userId = self::$app->session->get('user');
        if (!$userId) return false;
        
        $db = self::$app->getDatabase();
        $stmt = $db->prepare("SELECT role FROM utilisateurs WHERE id_utilisateur = $1");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        return $user && $user['role'] === 'patient';
    }
    
    public static function isMedecin(): bool {
        $userId = self::$app->session->get('user');
        if (!$userId) return false;
        
        $db = self::$app->getDatabase();
        $stmt = $db->prepare("SELECT role FROM utilisateurs WHERE id_utilisateur = $1");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        return $user && $user['role'] === 'medecin';
    }
}
