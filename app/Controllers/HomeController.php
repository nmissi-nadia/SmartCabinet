<?php

namespace App\Controllers;

class HomeController {
    public function index() {
        $title = 'Accueil';
        $currentPage = 'home';
        
        ob_start();
        // require_once __DIR__ . '/../Views/home/index.php';
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../Views/layouts/main.php';
    }
}
