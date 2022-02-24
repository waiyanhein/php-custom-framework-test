<?php

interface IDatabaseDriver
{
    public function connect();

    public function executeWriteQuery($sql, $parameters = [ ]);

    public function execute($sql);

    public function select($sql, $parameters = [ ]);
}
