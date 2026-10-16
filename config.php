<?php
/**
 * Set error messag
 * Only show errors when APP_DEBUG=1 is set in the environment
 */
define('APP_DEBUG', getenv('APP_DEBUG') === '1');
error_reporting(E_ALL);
ini_set('display_errors', APP_DEBUG ? 1 : 0);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_httponly', 1);
session_start();
date_default_timezone_set('Europe/London');

/**
 * Define root dir
 */
define('ROOT_DIR', dirname(__FILE__));

/**
 * Define database setting
 * Values can be overridden with environment variables
 */
define('DATABASE_PORT', getenv('DB_PORT') ?: 3306);
define('DATABASE_HOST', getenv('DB_HOST') ?: 'localhost');
define('DATABASE_NAME', getenv('DB_NAME') ?: 'test');
define('DATABASE_USER', getenv('DB_USER') ?: 'root');
define('DATABASE_PASS', getenv('DB_PASS') ?: 'root');

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
    $fileName = ROOT_DIR . DIRECTORY_SEPARATOR . $fileName . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    if (file_exists($fileName) === true) {
        include_once $fileName;
    }
}
spl_autoload_register('autoload');
