<?php

interface ILogService
{
    public function logSuccessMessage(string $message);

    public function logError(Exception $exception);
}