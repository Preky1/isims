<?php

declare(strict_types=1);

session_start();

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'app');

require BASE_PATH . '/config/bootstrap.php';

$router = require BASE_PATH . '/routes/web.php';

$method = $_SERVER['REQUEST_METHOD'];
// Allow HTML forms to send DELETE via hidden _method field
if ($method === 'POST' && isset($_POST['_method'])) {
    $override = strtoupper($_POST['_method']);
    if (in_array($override, ['DELETE', 'PATCH', 'PUT'], true)) {
        $method = $override;
    }
}

$router->dispatch($method, parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
