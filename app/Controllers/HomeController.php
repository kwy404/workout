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

        //View on resources/views/welcome.blade.php
        // welcome pass on makeView
        return new Response(BladeConfig::makeView('welcome2', compact('name', 'age')));
    }
}
