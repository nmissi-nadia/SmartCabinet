<?php
namespace App\Core;

class Session {
    private const FLASH_KEY = '_flash';
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Initialiser le tableau des messages flash s'il n'existe pas
        if (!isset($_SESSION[self::FLASH_KEY])) {
            $_SESSION[self::FLASH_KEY] = [];
        }
    }
    
    public function set(string $key, $value) {
        $_SESSION[$key] = $value;
    }
    
    public function get(string $key) {
        return $_SESSION[$key] ?? null;
    }
    
    public function remove(string $key) {
        unset($_SESSION[$key]);
    }
    
    public function destroy() {
        session_destroy();
        $_SESSION = [];
    }
    
    public function hasFlash(string $key): bool {
        return isset($_SESSION[self::FLASH_KEY][$key]);
    }
    
    public function setFlash(string $key, $message) {
        $_SESSION[self::FLASH_KEY][$key] = $message;
    }
    
    public function getFlash(string $key) {
        if ($this->hasFlash($key)) {
            $message = $_SESSION[self::FLASH_KEY][$key];
            unset($_SESSION[self::FLASH_KEY][$key]);
            return $message;
        }
        return null;
    }
    
    public function clearFlash() {
        unset($_SESSION[self::FLASH_KEY]);
        $_SESSION[self::FLASH_KEY] = [];
    }
}
