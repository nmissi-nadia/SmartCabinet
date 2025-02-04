<?php
namespace App\Core;

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = ['method' => $method, 'path' => $path, 'handler' => $handler];
    }

    public function dispatch($uri) {
        foreach ($this->routes as $route) {
            if ($route['path'] === $uri && $_SERVER['REQUEST_METHOD'] === $route['method']) {
                list($controller, $action) = explode('@', $route['handler']);
                $controller = "App\\Controllers\\$controller";
                $controllerInstance = new $controller();
                $controllerInstance->$action();
                return;
            }
        }
        http_response_code(404);
        echo "Page non trouvée";
    }
}
?>