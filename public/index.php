<?php

require_once __DIR__ . '/../vendor/autoload.php';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

try {
    $request = Symfony\Component\HttpFoundation\Request::createFromGlobals();

    $router = require_once __DIR__ . '/../config/routes.php';

    $response = $router->dispatch($request);

    $response->send();
} catch (\Exception $e) {
    $response = new Symfony\Component\HttpFoundation\Response(
        $whoops->handleException($e),
        500,
        array('Content-Type' => 'text/html')
    );

    $response->send();
}