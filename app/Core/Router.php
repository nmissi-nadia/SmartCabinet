<?php
namespace App\Core;

class Router {
    protected array $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve() {
        $path = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        // Supprimer les paramètres de requête
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }

        // Supprimer le baseUrl s'il existe
        $baseUrl = Application::$app->getBaseUrl();
        if (strpos($path, $baseUrl) === 0) {
            $path = substr($path, strlen($baseUrl));
        }

        // S'assurer que le chemin commence par /
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        // Chercher une correspondance exacte d'abord
        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];
            if (is_array($callback)) {
                $controller = new $callback[0]();
                return call_user_func([$controller, $callback[1]]);
            }
            return call_user_func($callback);
        }

        // Si pas de correspondance exacte, chercher les routes avec paramètres
        foreach ($this->routes[$method] as $route => $callback) {
            // Convertir la route en expression régulière
            $pattern = preg_replace('/\{(\w+)\}/', '([^\/]+)', $route);
            $pattern = "@^" . $pattern . "$@D";

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Supprimer la correspondance complète
                
                if (is_array($callback)) {
                    $controller = new $callback[0]();
                    return call_user_func_array([$controller, $callback[1]], $matches);
                }
                
                return call_user_func_array($callback, $matches);
            }
        }

        // Route non trouvée
        http_response_code(404);
        if (file_exists(Application::$ROOT_DIR . '/views/_404.php')) {
            include Application::$ROOT_DIR . '/views/_404.php';
        } else {
            echo "Page non trouvée";
        }
        return '';
    }

    public function renderView($view, $params = []) {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        
        ob_start();
        include Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}
