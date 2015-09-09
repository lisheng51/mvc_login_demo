<?php
/**
 * Set error messag
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
date_default_timezone_set('Europe/London');

/**
 * Define root dir
 */
define('ROOT_DIR', dirname(__FILE__));

/**
 * Define database setting
 */
define('DATABASE_PORT', 3306);
define('DATABASE_HOST', 'localhost');
define('DATABASE_NAME', 'test');
define('DATABASE_USER', 'root');
define('DATABASE_PASS', 'root');

/**
 * Autoload class with namespace
 * 
 * @param string $className
 */
function autoload($className) {
    $className = ltrim($className, '\\');
    $fileName = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    if (file_exists($fileName) === true) {
        include_once $fileName;
    } 
}
spl_autoload_register('autoload');
