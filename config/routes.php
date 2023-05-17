<?php
use App\Routing\Router;
use App\Controllers\UserController;
use App\Controllers\HomeController;

$router = new Router();

$router->addRoute('/', [HomeController::class, 'index']);
$router->addRoute('/welcome', [HomeController::class, 'index']);

$router->addRoute('/user/{id}', [UserController::class, 'show']);

return $router;
