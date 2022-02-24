<?php

require 'autoload.php';

// IoC container. In the future we can extend the framework and make use of this
// this will help us in mocking classes in testing
$container = new Container();
$container->bindServices();
$databaseService = $container->get(IDatabaseService::class);
$databaseService->connect();
$fileService = $container->get(IFileService::class);

// TODO: in the future, we can implement our routing system and create controllers and view files and extend the framework
echo "<html>";
echo "<head><title>Technologi</title></head>";
echo "<body>";
require 'Views/form.php';
if (isset($_GET['keyword'])) {
    // SQL injection is already prevented using parameterised query
    $result = $fileService->search(trim($_GET['keyword']));
    require 'Views/result.php';

}
echo "</body>";