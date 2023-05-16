<?php

use Dotenv\Dotenv;

class PHPServer
{
    private $port;
    private $host;

    public function __construct($envFilePath)
    {
        $dotenv = Dotenv::createImmutable(dirname($envFilePath));
        $dotenv->load();

        $this->port = $_ENV['APP_PORT'] ?? 3000;
        $this->host = $_ENV['APP_HOST'] ?? 'localhost';
    }

    public function startServer()
    {
        // Definir configurações de exibição de erros do PHP
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        // Adicionar manipulador de erros personalizado
        set_error_handler(function ($severity, $message, $file, $line) {
            // Exiba o erro personalizado ou registre em um arquivo de log
            echo "Error: $message in $file on line $line";
        });

        $command = sprintf('php -S %s:%d -t public', $this->host, $this->port);

        $startMessage = sprintf("\033[1mStarting PHP server at \033[34mhttp://%s:%d\033[0m\n", $this->host, $this->port);

        echo $startMessage;

        passthru($command);
    }
}
