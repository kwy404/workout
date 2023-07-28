<?php
use App\Routing\Router;
use App\Controllers\UserController;
use App\Controllers\HomeController;
use App\Controllers\TesteController;

$router = new Router();

$router->addRoute('GET', '/', [HomeController::class, 'index']);
$router->addRoute('GET', '/teste', [TesteController::class, 'index']);
$router->addRoute('GET', '/welcome', [HomeController::class, 'index']);
$router->addRoute('GET', '/user/{id}/{token}', [UserController::class, 'show']);

return $router;