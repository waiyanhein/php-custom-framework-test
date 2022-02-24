<?php

require 'autoload.php';

$container = new Container();
$container->bindServices();
$databaseService = $container->get(IDatabaseService::class);
$databaseService->connect();

$fileService = $container->get(IFileService::class);
// $fileService->importFiles();
//
//$result = $fileService->search('Image');
//var_dump($result);
$result = $fileService->getFullPath(new File(18, 'Mysql.com', 16));
var_dump($result);