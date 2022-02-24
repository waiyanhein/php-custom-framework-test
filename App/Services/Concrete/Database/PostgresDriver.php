<?php

// TODO: provide implementation
class PostgresDriver implements IDatabaseDriver
{
    public function __construct()
    {
    }

    public function connect()
    {
        // TODO: Implement connect() method.
    }

    public function executeWriteQuery($sql, $parameters = [])
    {
        // TODO: Implement executeWriteQuery() method.
    }

    public function select($sql, $parameters = [])
    {
        // TODO: Implement select() method.
    }

    public function beginTransaction()
    {
        // TODO: Implement beginTransaction() method.
    }

    public function commitTransaction()
    {
        // TODO: Implement commitTransaction() method.
    }

    public function rollbackTransaction()
    {
        // TODO: Implement rollbackTransaction() method.
    }

    public function execute($sql)
    {
        // TODO: Implement execute() method.
    }
}
