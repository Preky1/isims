<?php

declare(strict_types=1);

spl_autoload_register(function (string $class): void {
    $paths = [
        APP_PATH . '/controllers/' . $class . '.php',
        APP_PATH . '/models/' . $class . '.php',
        APP_PATH . '/middleware/' . $class . '.php',
        APP_PATH . '/helpers/' . $class . '.php',
        BASE_PATH . '/config/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (is_file($path)) {
            require $path;
            return;
        }
    }
});

require APP_PATH . '/helpers/functions.php';

Env::load(BASE_PATH . '/.env');

if ((bool) env('APP_DEBUG', false)) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

date_default_timezone_set('Africa/Johannesburg');
