<?php

class MySqlDriver implements IDatabaseDriver
{
    private $logService;
    private $pdo;

    public function __construct(ILogService $logService)
    {
        $this->logService = $logService;
    }

    public function connect()
    {
        $host = getenv('DB_HOST');
        $dbName = getenv('DB_NAME');
        $port = getenv('DB_PORT');
        $username = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');

        $dsn = "mysql:host=$host;port=$port;dbname=$dbName;charset=UTF8";
        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            if ($pdo) {
                // successfully connected to the database
                $this->pdo = $pdo;
            }
        } catch (PDOException $e) {
            $this->logService->logError($e);
        }
    }

    public function executeWriteQuery($sql, $parameters = [])
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($parameters);
    }

    public function execute($sql)
    {
        $this->pdo->exec($sql);
    }

    public function select($sql, $parameters = [])
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($parameters);

        return $statement->fetchAll();
    }

    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    public function commitTransaction()
    {
        $this->pdo->commit();
    }

    public function rollbackTransaction()
    {
        $this->pdo->rollback();
    }
}
