<?php
namespace App\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Router
{
    private $routes = [];

    public function addRoute(string $path, $handler)
    {
        // Adiciona uma nova rota ao array de rotas
        $this->routes[$path] = $handler;
    }

    public function dispatch(Request $request): Response
    {
        // Obtém o caminho da solicitação recebida
        $path = $request->getPathInfo();

        // Encontra o manipulador de rota para o caminho
        $handler = $this->findRouteHandler($path);

        if ($handler === null) {
            // Se não houver um manipulador correspondente, retorna uma resposta 404 (Página não encontrada)
            include '../resources/views/404.php';
            $content = ob_get_clean();
            return new Response($content, 404);
        }

        if (is_callable($handler)) {
            // Se o manipulador for uma função anônima ou um método de classe estático, chama-o diretamente
            return call_user_func($handler);
        }

        if (is_array($handler)) {
            // Se o manipulador for um array, assume que é um controlador de classe e um método
            [$controllerClass, $method] = $handler;

            if (!class_exists($controllerClass)) {
                // Se a classe do controlador não existir, retorna uma resposta 404
                return new Response('404 Not Found', 404);
            }

            $controller = new $controllerClass();

            if (!method_exists($controller, $method)) {
                // Se o método do controlador não existir, retorna uma resposta 404
                return new Response('404 Not Found', 404);
            }

            $routeParams = $this->extractRouteParams($path);

            // Chama o método do controlador passando os parâmetros da rota
            return call_user_func_array([$controller, $method], [$routeParams]);
        }

        // Se o manipulador não for uma função, nem um controlador válido, retorna uma resposta 500 (Erro interno do servidor)
        return new Response('500 Internal Server Error', 500);
    }

    private function findRouteHandler(string $path)
    {
        // Remove a barra final, se houver, para evitar inconsistências
        $path = rtrim($path, '/');

        if ($path === '') {
            $path = '/';
        }

        foreach ($this->routes as $routePath => $handler) {
            if ($routePath === $path) {
                // Se houver uma correspondência exata do caminho da rota, retorna o manipulador
                return $handler;
            }

            $pattern = $this->getRoutePattern($routePath);
            if (preg_match($pattern, $path, $matches)) {
                // Se o caminho corresponder a um padrão de rota, retorna o manipulador
                array_shift($matches);
                return $handler;
            }
        }

        // Se nenhum manipulador for encontrado para o caminho, retorna null
        return null;
    }

    private function extractRouteParams(string $path): array
    {
        $routeParams = [];

        foreach ($this->routes as $routePath => $handler) {
            $pattern = $this->getRoutePattern($routePath);
            if (preg_match($pattern, $path, $matches)) {
                // Se o caminho corresponder a um padrão de rota, extrai os parâmetros da rota
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
            // Se a rota for apenas a barra, retorna o padrão correspondente
            return '/^\/$/';
        }
    
        // Transforma o caminho da rota em um padrão de expressão regular
        $pattern = preg_replace('/\//', '\/', $routePath);
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^\/]+)', $pattern);
        return '/^' . $pattern . '$/';
    }
}

