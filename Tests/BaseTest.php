<?php

class BaseTest
{
    private $testBeingRun = null;
    protected $logService;
    protected $databaseService;

    public function __construct()
    {
        $this->logService = new LogService();
        $this->databaseService = new DatabaseService($this->logService);
        $this->databaseService->connect();
        // running the migration clears the existing data in the database
        $this->databaseService->migrate();
    }

    public function beforeEach(): void
    {

    }

    public function afterEach(): void
    {
        // running the migration clears the existing data in the database
        $this->databaseService->migrate();
    }

    public function runTests($testToRun = null)
    {
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            // if the method name is not current method name and starts with "test", then execute the method
            // or beforeEach afterEach
            if (!in_array($method, [ 'runTests', 'beforeEach', 'afterEach' ]) && substr($method, 0, 4) == 'test') {
                // also ensure that run only one specific test when the parameter is passed to filter the test
                if (($testToRun && $testToRun == $method) or empty($testToRun)) {
                    $this->testBeingRun = $method;
                    $this->beforeEach();
                    $this->{$method}();
                    $this->afterEach();
                    $this->testBeingRun = null;
                }
            }
        }
    }

    public function assertEqual($expected, $actual)
    {
        if ($expected == $actual) {
            echo "\033[32m Assertion passed: $this->testBeingRun - $expected and $actual are equal.\n \033[0m\n";
        } else {
            echo "\033[31m Assertion failed: $this->testBeingRun - $expected and $actual are not equal.\n \033[0m\n";
        }
    }
}
