<?php

$GLOBALS['is_test'] = true;
require 'autoload.php';
$container = new Container();
$testToRun = null;
if (isset($argc)) {
    for ($i = 0; $i < $argc; $i++) {
        if ($i == 1) {
            // specific test method name
            $testToRun = $argv[$i];
        }
    }
}
$unitTests = [
    new FileServiceTest()
];

foreach ($unitTests as $unitTest) {
    $unitTest->runTests($testToRun);
}
