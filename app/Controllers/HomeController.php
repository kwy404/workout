<?php

namespace App\Controllers;

use App\Config\BladeConfig;
use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    private $blade;

    public function __construct()
    {
        $this->blade = BladeConfig::getBladeInstance();
    }

    public function index()
    {
        $name = 'kwy404';
        $age = 23;

        $viewData = [
            'name' => $name,
            'age' => $age
        ];

        $html = $this->blade->make('welcome', $viewData)->render();

        return new Response($html);
    }
}
