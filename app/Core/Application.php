<?php
namespace App\Core;

use App\Controllers\AuthController;
use App\Controllers\PatientController;
use App\Controllers\MedecinController;

class Application {
    public static Application $app;
    public static string $ROOT_DIR;
    public Router $router;
    public Session $session;
    public Database $database;
    public string $baseUrl = '/SmartCabinet';
    
    public function __construct() {
        self::$ROOT_DIR = dirname(__DIR__);
        self::$app = $this;
        
        // Initialiser la session avant tout
        $this->session = new Session();
        
        // Initialiser les autres composants
        $this->router = new Router();
        $this->database = new Database();
        
        // Définir les routes
        $this->initRoutes();
    }
    
    protected function initRoutes() {
        // Routes d'authentification
        $this->router->get($this->baseUrl . '/auth/login', [AuthController::class, 'login']);
        $this->router->post($this->baseUrl . '/auth/login', [AuthController::class, 'login']);
        $this->router->get($this->baseUrl . '/auth/register', [AuthController::class, 'register']);
        $this->router->post($this->baseUrl . '/auth/register', [AuthController::class, 'register']);
        $this->router->get($this->baseUrl . '/auth/logout', [AuthController::class, 'logout']);
        
        // Routes patient
        $this->router->get($this->baseUrl . '/patient/dashboard', [PatientController::class, 'dashboard']);
        $this->router->get($this->baseUrl . '/patient/profile', [PatientController::class, 'profile']);
        $this->router->post($this->baseUrl . '/patient/profile', [PatientController::class, 'profile']);
        $this->router->get($this->baseUrl . '/patient/appointments', [PatientController::class, 'appointments']);
        $this->router->post($this->baseUrl . '/patient/appointment/cancel', [PatientController::class, 'cancelAppointment']);
        
        // Routes médecin
        $this->router->get($this->baseUrl . '/medecin/dashboard', [MedecinController::class, 'dashboard']);
        $this->router->get($this->baseUrl . '/medecin/profile', [MedecinController::class, 'profile']);
        $this->router->post($this->baseUrl . '/medecin/profile', [MedecinController::class, 'profile']);
        $this->router->get($this->baseUrl . '/medecin/appointment/confirm/{id}', [MedecinController::class, 'confirmAppointment']);
        $this->router->get($this->baseUrl . '/medecin/appointment/cancel/{id}', [MedecinController::class, 'cancelAppointment']);
    }
    
    public function run() {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            http_response_code(500);
            echo $e->getMessage();
        }
    }
    
    public function getBaseUrl(): string {
        return $this->baseUrl;
    }
    
    public function getDatabase(): Database {
        return $this->database;
    }
    
    public function getSession(): Session {
        return $this->session;
    }
}
