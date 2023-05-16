#!/usr/bin/env php
<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LogHandler
{
    private $log;

    public function __construct()
    {
        $this->log = new Logger('name');
        $this->log->pushHandler(new StreamHandler('logs/log.log', 300));
    }

    public function addErrorLogWarning($message)
    {
        $this->log->warning($message);
    }

    public function addErrorLogError($message)
    {
        $this->log->error($message);
    }
}
