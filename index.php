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
if (isset($_GET['c']) === true) {
    $controller = $_GET['c'];
}

if (empty($controller) === false) {
    switch ($controller) {
        case "login":
            $obj = new \controller\Login();
            $obj->execute();
            break;
        case "logout":
            $obj = new \controller\Logout();
            $obj->execute();
            break;
        case "profiel":
            $obj = new \controller\Profiel();
            $obj->execute();
            break;
        default:
            echo "none";
            break;
    }
} else {
    echo "no controller";
}