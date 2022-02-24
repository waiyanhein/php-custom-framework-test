<?php

interface IDatabaseService
{
    // get driver instance
    public function getDriver(): IDatabaseDriver;

    public function connect();

    public function executeWriteQuery($sql, $parameters = [ ]);

    public function migrate();

    public function beginTransaction();

    public function commitTransaction();

    public function rollbackTransaction();
}
