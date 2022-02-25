<?php

require 'autoload.php';

// IoC container. In the future we can extend the framework and make use of this
// this will help us in mocking classes in testing
$container = new Container();
$container->bindServices();
$logService = $container->get(ILogService::class);
$databaseService = $container->get(IDatabaseService::class);
$databaseService->connect();
$databaseService->migrate();

$logService->logSuccessMessage("Database migration has been run.");