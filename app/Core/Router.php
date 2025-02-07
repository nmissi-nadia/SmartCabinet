<?php
namespace App\Core;

class Router {
    protected array $routes = [];
    protected array $params = [];
    public Request $request;

    public function __construct() {
        $this->request = new Request();
    }

    public function get(string $path, string $callback): void {
        $this->routes['get'][$path] = $callback;
    }

    public function post(string $path, string $callback): void {
        $this->routes['post'][$path] = $callback;
    }

    protected function getCallback(): ?array {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();
        $url = trim($url, '/');

        // Get all routes for current request method
        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route => $callback) {
            $route = trim($route, '/');
            
            // Convert route parameters to regex pattern
            $routeRegex = "@^" . preg_replace('/\{(\w+)(:[^}]+)?}/', '(?P<\1>[^/]+)', $route) . "$@";
            
            if (preg_match($routeRegex, $url, $matches)) {
                $this->params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return $this->parseCallback($callback);
            }
        }
        
        return null;
    }

    protected function parseCallback(string $callback): array {
        // Format: ControllerName@methodName
        [$controller, $method] = explode('@', $callback);
        
        // Add namespace
        $controller = "App\\Controllers\\$controller";
        
        return [$controller, $method];
    }

    public function resolve() {
        $callback = $this->getCallback();
        
        if ($callback === null) {
            http_response_code(404);
            require_once Application::$ROOT_DIR . '/views/_404.php';
            return;
        }

        [$controller, $method] = $callback;
        
        if (!class_exists($controller)) {
            throw new \Exception("Controller $controller does not exist");
        }
        
        $controller = new $controller();
        
        if (!method_exists($controller, $method)) {
            throw new \Exception("Method $method does not exist in controller $controller");
        }
        
        // Pass URL parameters to the method if they exist
        if (!empty($this->params)) {
            unset($this->params[0]); // Remove full match
            return call_user_func_array([$controller, $method], array_values($this->params));
        }
        
        return call_user_func([$controller, $method]);
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
