<?php

/**
 * Initialisations
 */

// Load Dependencies with Composer EXPERIMENT
require_once(__DIR__ . '/../vendor/autoload.php');

// Register autoload function
spl_autoload_register('myAutoloader');

/**
 * Autoloader
 *
 * @param string $className  The name of the class
 * @return void
 */
function myAutoloader($className)
{
  require dirname(dirname(__FILE__)) . '/classes/' . $className . '.class.php';
}

// // Initialize "Whoops" HAS ISSUES
// $whoops = new Whoops\Run;
// $whoops->pushHandler(new Whoops\Handler\PrettyPageHandler);
// $whoops->register();

//// WAT DIS?
//extension = php_mbstring.dll;

// Authorisation
Auth::init();

// Read ".env" file
try {
    $dotenv = new Dotenv\Dotenv(__DIR__.'/..');
    $dotenv->load();
} catch (Exception $e) {
    // No .env file found.
}