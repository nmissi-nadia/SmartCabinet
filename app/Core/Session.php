<?php
namespace App\Core;

class Session {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
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
        return isset($_SESSION['_flash'][$key]);
    }
    
    public function setFlash(string $key, $message) {
        $_SESSION['_flash'][$key] = $message;
    }
    
    public function getFlash(string $key) {
        if ($this->hasFlash($key)) {
            $message = $_SESSION['_flash'][$key];
            unset($_SESSION['_flash'][$key]);
            return $message;
        }
        return null;
    }
    
    public function clearFlash() {
        unset($_SESSION['_flash']);
    }
}
