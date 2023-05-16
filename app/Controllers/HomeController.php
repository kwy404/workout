<?php

namespace App\Controllers;

use App\Config\BladeConfig;
use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    public function index()
    {
        $name = 'kwy404';
        $age = 23;

        $viewData = [
            'name' => $name,
            'age' => $age
        ];

        $html = BladeConfig::makeView('welcome', $viewData);

        return new Response($html);
    }
}
