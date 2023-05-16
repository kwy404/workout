<?php

namespace App\Config;

use Jenssegers\Blade\Blade;

class BladeConfig
{
    public static function getBladeInstance()
    {
        $viewsPath = realpath(__DIR__ . '/../../resources/views');
        $cachePath = realpath(__DIR__ . '/../../storage/cache/views');

        if (!$viewsPath || !$cachePath) {
            throw new \Exception("Invalid views or cache path");
        }

        return new Blade($viewsPath, $cachePath);
    }

    public static function makeView($view, $data = [])
    {
        $blade = self::getBladeInstance();
        return $blade->make($view, $data)->render();
    }
}
