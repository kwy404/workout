<?php

namespace App\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Config\BladeConfig;

class Router
{
    private $routes = [];

    public function addRoute(string $method, string $path, $handler)
    {
        if ($path === '/docs' || $path === '/workoutjson') {
            throw new \Exception("Cannot create routes for native Workout paths");
        }
        $this->routes['GET']['/workoutjson'] = $handler;
        // Adiciona uma nova rota ao array de rotas, usando o método HTTP como chave
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(Request $request): Response
    {
        // Obtém o método e o caminho da solicitação recebida
        $method = $request->getMethod();
        $path = rtrim($request->getPathInfo(), '/'); // Remove a barra final, se houver

        if ($path === '/workoutjson' && $method === 'GET') {
            // Rota especial para retornar todas as rotas em JSON
            $routesData = $this->getAllRoutesData();
            return new Response($routesData, 200, ['Content-Type' => 'application/json']);
        }

        // Verifica se existe um manipulador de rota para o método e caminho correspondentes
        $handler = $this->findRouteHandler($method, $path);

        if ($path === '/docs' && $method === 'GET') {
            // Rota especial para renderizar a página /app/docs/docs.blade.php com o template engine Blade
            $content = $this->renderDocsPage();
            return new Response($content, 200);
        }

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

    private function findRouteHandler(string $method, string $path)
    {
        // Remove a barra final, se houver, para evitar inconsistências
       
        $path = rtrim($path, '/');

        if ($path === '') {
            $path = '/';
        }

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routePath => $handler) {
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
        }

        // Se nenhum manipulador for encontrado para o método e caminho, retorna null
        return null;
    }

    private function extractRouteParams(string $path): array
    {
        $routeParams = [];

        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $routePath => $handler) {
                $pattern = $this->getRoutePattern($routePath);
                if (preg_match($pattern, $path, $matches)) {
                    // Se o caminho corresponder a um padrão de rota, extrai os parâmetros da rota
                    array_shift($matches);
                    $routeParams = $matches;
                    break;
                }
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

        // Remove uma barra opcional no final do caminho da rota
        $routePath = rtrim($routePath, '/');

        // Transforma o caminho da rota em um padrão de expressão regular
        $pattern = preg_replace('/\//', '\/', $routePath);
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^\/]+)', $pattern);
        return '/^' . $pattern . '$/';
    }

    private function getAllRoutesData(): string
    {
        // Obtém o título e a descrição do arquivo .env
        $title = env('APP_TITLE', 'Workout API');
        $description = env('APP_DESCRIPTION', 'API');

        $routesData = [
            'workout' => '1.0.0',
            'info' => [
                'title' => $title,
                'description' => $description,
                'version' => '0.1'
            ],
            'paths' => []
        ];

        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $path => $handler) {
                $routeData = [
                    $path => [
                        $method => [
                            'summary' => 'Root',
                            'operationId' => 'root__get',
                            'responses' => [
                                '200' => [
                                    'description' => 'Successful Response',
                                    'content' => [
                                        'application/json' => [
                                            'schema' => []
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];

                $routesData['paths'] = array_merge_recursive($routesData['paths'], $routeData);
            }
        }

        return json_encode($routesData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function renderDocsPage(): string
    {
        // Aqui você pode adicionar a lógica para renderizar a página usando o template engine Blade
        // Para simplificar o exemplo, vamos apenas retornar uma string com o conteúdo da página
        // Renderiza o template Blade e passa as variáveis para ele
        $routesData = $this->getAllRoutesData();
        $content = BladeConfig::getDocsView();
        return new Response($content);
    }
}
