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

    public static function makeViewWithCompact($view, ...$variables)
    {
        $data = compact(...$variables);
        return self::makeView($view, $data);
    }

    public static function getDocsView()
    {
        $viewPath = realpath(__DIR__ . '/../../app/docs/docs.blade.php');
        if (!$viewPath) {
            throw new \Exception("Invalid view path");
        }

        $viewsPath = realpath(__DIR__ . '/../../app/docs');
        $cachePath = realpath(__DIR__ . '/../../storage/cache/views');

        if (!$viewsPath || !$cachePath) {
            throw new \Exception("Invalid views or cache path");
        }

        // Cria uma nova instância do Blade com o novo caminho das views
        $blade = new Blade($viewsPath, $cachePath);

        // Renderiza a view de docs e retorna o conteúdo
        return $blade->make('docs')->render();
    }
}
