<?php
/**
 * if missing htaccess file:
 * RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?c=$1 [L]
 */
require 'config.php';

$controller = null;
if (isset($_GET['c']) === true && is_string($_GET['c']) === true) {
    $controller = trim($_GET['c'], '/');
}

$routes = array(
    "login" => "\\controller\\Login",
    "register" => "\\controller\\Register",
    "logout" => "\\controller\\Logout",
    "profiel" => "\\controller\\Profiel",
    "admin" => "\\controller\\Admin",
    "member" => "\\controller\\Member",
);

if (empty($controller) === true) {
    header("location: /login");
    exit();
}

if (isset($routes[$controller]) === true) {
    $obj = new $routes[$controller]();
    $obj->execute();
} else {
    http_response_code(404);
    echo "Page not found";
}
