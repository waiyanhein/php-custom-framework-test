<?php

require 'Container.php';

// autoload models
spl_autoload_register(function ($class_name) {
    $path = __DIR__ . '/App/Models/' . $class_name . '.php';
    if (file_exists($path)) {
        include $path;
    }
});

// autoload abstract services.
spl_autoload_register(function ($class_name) {
    $path = __DIR__ . '/App/Services/Abstraction/' . $class_name . '.php';
    if (file_exists($path)) {
        include $path;
    }
});

// autoload concrete services.
spl_autoload_register(function ($class_name) {
    $path = __DIR__ . '/App/Services/Concrete/' . $class_name . '.php';
    if (file_exists($path)) {
        include $path;
    }
});

// autoload database driver classes - now it only supports mysql (PDO)
spl_autoload_register(function ($class_name) {
    $path = __DIR__ . '/App/Services/Concrete/Database/' . $class_name . '.php';
    if (file_exists($path)) {
        include $path;
    }
});

// autoload the classes in the root directory such as Container.php (service container class)
spl_autoload_register(function ($class_name) {
    $path = __DIR__ . '/' . $class_name . '.php';
    if (file_exists($path)) {
        include $path;
    }
});

// autoload classes for unit tests
spl_autoload_register(function ($class_name) {
    $path = __DIR__ . '/Tests/Unit/' . $class_name . '.php';
    if (file_exists($path)) {
        include $path;
    }
});

// autoload classes for feature tests
spl_autoload_register(function ($class_name) {
    $path = __DIR__ . '/Tests/Feature/' . $class_name . '.php';
    if (file_exists($path)) {
        include $path;
    }
});

// autoload classes in the Tests root directory
spl_autoload_register(function ($class_name) {
    $path = __DIR__ . '/Tests/' . $class_name . '.php';
    if (file_exists($path)) {
        include $path;
    }
});

// for test, it loads .env.test file, otherwise, it loads .env file
$envFilePath = ((isset($GLOBALS['is_test']) and $GLOBALS['is_test']))? '/.env.test': '/.env';
(new DotEnv(__DIR__ . $envFilePath))->load(); // enable .env file support
// load the other required files
require 'globals.php';
require 'functions.php';
