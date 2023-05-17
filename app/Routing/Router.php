<?php
namespace App\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Router
{
    private $routes = [];

    public function addRoute(string $path, $handler)
    {
        $this->routes[$path] = $handler;
    }

    public function dispatch(Request $request): Response
    {
        $path = $request->getPathInfo();
        $handler = $this->findRouteHandler($path);

        if ($handler === null) {
            include '../resources/views/404.php';
            $content = ob_get_clean();
            return new Response($content, 404);
        }

        if (is_callable($handler)) {
            return call_user_func($handler);
        }

        if (is_array($handler)) {
            [$controllerClass, $method] = $handler;

            if (!class_exists($controllerClass)) {
                return new Response('404 Not Found', 404);
            }

            $controller = new $controllerClass();

            if (!method_exists($controller, $method)) {
                return new Response('404 Not Found', 404);
            }

            $routeParams = $this->extractRouteParams($path);

            return call_user_func_array([$controller, $method], [$routeParams]);
        }

        return new Response('500 Internal Server Error', 500);
    }

    private function findRouteHandler(string $path)
    {
        $path = rtrim($path, '/');

        if ($path === '') {
            $path = '/';
        }

        foreach ($this->routes as $routePath => $handler) {
            if ($routePath === $path) {
                return $handler;
            }

            $pattern = $this->getRoutePattern($routePath);
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                return $handler;
            }
        }

        return null;
    }

    private function extractRouteParams(string $path): array
    {
        $routeParams = [];

        foreach ($this->routes as $routePath => $handler) {
            $pattern = $this->getRoutePattern($routePath);
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                $routeParams = $matches;
                break;
            }
        }

        return $routeParams;
    }

    private function getRoutePattern(string $routePath): string
    {
        if ($routePath === '/') {
            return '/^\/$/';
        }
    
        $pattern = preg_replace('/\//', '\/', $routePath);
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^\/]+)', $pattern);
        return '/^' . $pattern . '$/';
    }
}
