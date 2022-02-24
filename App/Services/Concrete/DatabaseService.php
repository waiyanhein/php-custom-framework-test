<?php

class DatabaseService implements IDatabaseService
{
    private $logService;
    private $driverInstance;

    public function __construct(ILogService $logService)
    {
        $this->logService = $logService;
    }

    public function getDriver(): IDatabaseDriver
    {
        if ($this->driverInstance) {
            return $this->driverInstance;
        }

        $driver = getenv('DB_DRIVER');
        if ($driver == 'mysql') {
            $this->driverInstance = new MySqlDriver($this->logService);
        } else {
            // postgres is not used currently
            // TODO: provide implementation for PostgresDriver class and add more drivers
            $this->driverInstance = new PostgresDriver();
        }

        return $this->driverInstance;
    }

    public function connect()
    {
        $this->getDriver()->connect();
    }

    public function executeWriteQuery($sql, $parameters = [])
    {
        $this->getDriver()->executeWriteQuery($sql, $parameters);
    }

    public function select($sql, $parameters = [ ])
    {
        $this->getDriver()->select($sql, $parameters);
    }

    public function migrate()
    {
        $createFilesTableQuery = 'CREATE TABLE IF NOT EXISTS files( 
            id INT,
            path  VARCHAR(255) NOT NULL, 
            parent_file_id  INT NULL,
            PRIMARY KEY(id)
        );';
        $this->getDriver()->execute($createFilesTableQuery);
    }
}
