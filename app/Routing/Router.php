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
        $handler = $this->routes[$path] ?? null;

        if ($handler === null) {
            return new Response('404 Not Found', 404);
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

            return call_user_func([$controller, $method]);
        }

        return new Response('500 Internal Server Error', 500);
    }
}
