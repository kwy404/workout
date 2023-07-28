<?php

namespace App\Controllers;

use App\Config\BladeConfig;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function show($params)
    {
        $id = $params['id'];
        $token = $params['token'];
        $html = BladeConfig::makeView('user', compact('id', 'token'));
        return new Response($html);
    }
}
