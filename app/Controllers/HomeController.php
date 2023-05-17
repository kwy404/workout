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

        // Renderiza o template Blade e passa as variáveis para ele
        $content = BladeConfig::makeView('welcome', compact('logo', 'descricao'));
        return new Response($content);
    }
}
