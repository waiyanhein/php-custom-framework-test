<?php

// dependency injection container
class Container
{
    private $instances = [ ];

    public function set($id, $instance)
    {
        $instances = [ ];
        $replaced = false;
        foreach ($this->instances as $existingId => $existingInstance) {
            if ($existingId == $id) {
                $replaced = true;
                $instances[$id] = $instance;
            } else {
                $instances[$existingId] = $existingInstance;
            }
        }
        if (! $replaced) {
            // new binding
            $instances[$id] = $instance;
        }

        $this->instances = $instances;
    }

    public function get($id)
    {
        $instance = null;
        foreach ($this->instances as $existingId => $existingInstance) {
            if ($id == $existingId) {
                $instance = $existingInstance;
            }
        }

        return $instance;
    }

    public function bindServices()
    {
        $logService = new LogService();
        $databaseService = new DatabaseService($logService);
        $this->set(IFileService::class, new FileService($databaseService, $logService));
        $this->set(ILogService::class, $logService);
        $this->set(IDatabaseService::class, $databaseService);
    }
}
