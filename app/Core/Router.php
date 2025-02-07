<?php
namespace App\Core;

class Router {
    private array $routes = [];
    
    /**
     * Enregistre une route GET
     */
    public function get(string $path, array $callback) {
        $this->routes['GET'][$path] = $callback;
    }
    
    /**
     * Enregistre une route POST
     */
    public function post(string $path, array $callback) {
        $this->routes['POST'][$path] = $callback;
    }
    
    /**
     * Résout la route actuelle
     */
    public function resolve() {
        $path = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Supprimer les paramètres de requête de l'URL
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        
        $callback = $this->routes[$method][$path] ?? false;
        
        if ($callback === false) {
            http_response_code(404);
            return $this->renderView('404');
        }
        
        if (is_array($callback)) {
            $controller = new $callback[0]();
            $callback[0] = $controller;
        }
        
        return call_user_func($callback);
    }
    
    /**
     * Rend une vue avec le layout
     */
    public function renderView(string $view, array $params = []): string {
        $layoutContent = $this->layoutContent($params);
        $viewContent = $this->renderOnlyView($view, $params);
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }
    
    /**
     * Rend le layout
     */
    protected function layoutContent(array $params = []): string {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        
        // Récupérer le message flash s'il existe
        $flash = Application::$app->session->getFlash('message');
        
        ob_start();
        require_once Application::$ROOT_DIR . "/views/layouts/main.php";
        return ob_get_clean();
    }
    
    /**
     * Rend une vue sans le layout
     */
    protected function renderOnlyView(string $view, array $params = []): string {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        
        ob_start();
        require_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
    
    /**
     * Redirige vers une URL
     */
    public function redirect(string $url) {
        header("Location: $url");
        exit;
    }
}
