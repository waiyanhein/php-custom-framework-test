<?php

require 'autoload.php';

// IoC container. In the future we can extend the framework and make use of this
// this will help us in mocking classes in testing
$container = new Container();
$container->bindServices();
$databaseService = $container->get(IDatabaseService::class);
$databaseService->connect();
$fileService = $container->get(IFileService::class);
$logService = $container->get(ILogService::class);

$fileService->importFiles();

$logService->logSuccessMessage("Files have been imported.");