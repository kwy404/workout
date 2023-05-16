<?php

namespace App\Controllers;

use App\Config\BladeConfig;
use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    public function index()
    {
        $logo = 'Workout';
        $descricao = "Um framework PHP para desenvolvimento web";

        //View on resources/views/welcome.blade.php
        // welcome pass on makeView
        return new Response(BladeConfig::makeView('welcome', compact('logo', 'descricao')));
    }
}
