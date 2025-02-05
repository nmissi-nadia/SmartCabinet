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
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = $uri ?: '/';
        $method = $_SERVER['REQUEST_METHOD'];
    
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                
                continue;
            }
         
            if (preg_match($route['pattern'], $uri, $matches)) {
                // Extraire les paramètres de l'URL
                $this->params = array_filter(
                    $matches,
                    fn($key) => !is_numeric($key),
                    ARRAY_FILTER_USE_KEY
                );
                
              
                list($controllerName, $action) = explode('@', $route['handler']);
                $controller = "App\\Controllers\\$controllerName";
                var_dump($controller);
                if (!class_exists($controller)) {
                    
                    throw new \Exception("Controller not found: $controller");
                }

                $controllerInstance = new $controller();
                if (!method_exists($controllerInstance, $action)) {
                    throw new \Exception("Action not found: $action in $controller");
                }

                // Passer les paramètres à la méthode du contrôleur
                call_user_func_array([$controllerInstance, $action], $this->params);
                return;
            }
            
        }

        // Route non trouvée
        http_response_code(404);
        require_once __DIR__ . '/../app/Views/errors/404.php';
    }

    public function getParams(): array
    {
        return $this->params;
    }
}