<?php

class LogService implements ILogService
{
    public function logSuccessMessage(string $message)
    {
        echo "\033[32m $message\n \033[0m\n";
    }

    public function logError(Exception $exception)
    {
        echo "\033[31m Error: {$exception->getMessage()}\n \033[0m\n";
    }
}
