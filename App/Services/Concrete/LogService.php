<?php

class LogService implements ILogService
{
    public function logError(Exception $exception)
    {
        echo "\033[31m Error: {$exception->getMessage()}\n \033[0m\n";
    }
}
