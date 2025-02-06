<?php
namespace Core;

class Router {
    private array $routes = [];
    private array $params = [];

    public function add(string $method, string $path, string $handler): void
    {
        // Convertir les paramètres de route en expressions régulières
        $pattern = preg_replace('/\{([^}]+)\}/', '(?P<\1>[^/]+)', $path);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';

        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler,
            'path' => $path
        ];
    }
    public function dispatch(string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH) ?: '/';
        $method = $_SERVER['REQUEST_METHOD'];
    
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                $this->params = array_filter($matches, fn($key) => !is_numeric($key), ARRAY_FILTER_USE_KEY);
    
                [$controllerName, $action] = explode('@', $route['handler']);
                $controllerClass = "App\\Controllers\\$controllerName";
    
                if (!class_exists($controllerClass)) {
                    $this->handleError(404, "Controller not found: $controllerClass");
                    return;
                }
    
                $controller = new $controllerClass();
    
                if (!method_exists($controller, $action)) {
                    $this->handleError(404, "Action not found: $action in $controllerClass");
                    return;
                }
    
                // Appeler l'action avec les paramètres extraits
                call_user_func_array([$controller, $action], $this->params);
                return;
            }
        }
    
        // Route non trouvée
        $this->handleError(404, "Route not found: $uri");
    }
        
    private function handleError(int $statusCode, string $message): void
    {
        http_response_code($statusCode);
        require_once __DIR__ . '/../app/Views/errors/404.php';
        exit($message);
    }
    

    public function getParams(): array
    {
        return $this->params;
    }
}