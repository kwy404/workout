<?php

require_once __DIR__ . '/../vendor/autoload.php';

$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();

$router = require_once __DIR__ . '/../config/routes.php';

$response = $router->dispatch($request);

$response->send();
