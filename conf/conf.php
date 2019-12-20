<?php

/* configuration file */

try {
    $dotenv = Dotenv\Dotenv::createMutable('../conf/');
    $dotenv->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    echo "Error";
    var_dump($e);
}


if(getenv('APP_DEBUG')=="true") {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}