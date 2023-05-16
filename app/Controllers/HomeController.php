<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;
use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    public function index()
    {
        $viewsPath = __DIR__ . '/../../resources/views';
        $cachePath = __DIR__ . '/../../storage/cache/views';
        $blade = new Blade($viewsPath, $cachePath);

        $name = 'kwy404';
        $age = 23;

        $viewData = [
            'name' => $name,
            'age' => $age
        ];

        $html = $blade->make('welcome', $viewData)->render();

        return new Response($html);
    }
}
