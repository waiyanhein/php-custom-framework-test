<?php

interface ILogService
{
    public function logError(Exception $exception);
}