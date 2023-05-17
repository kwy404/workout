<?php
use App\Routing\Router;
use App\Controllers\UserController;
use App\Controllers\HomeController;

$router = new Router();

$router->addRoute('GET', '/', [HomeController::class, 'index']);
$router->addRoute('DELETE', '/welcome', [HomeController::class, 'index']);
$router->addRoute('POST', '/user/{id}/{token}', [UserController::class, 'show']);

return $router;