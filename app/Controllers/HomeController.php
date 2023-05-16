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

        return new Response(BladeConfig::makeView('welcome', compact('name', 'age')));
    }
}
