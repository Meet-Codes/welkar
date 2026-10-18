<?php

declare(strict_types=1);

// Welkar E-Commerce Master Public Entry Point
define('WELKAR_START', microtime(true));

// Autoloader
require_once __DIR__ . '/../app/Support/Autoloader.php';
\App\Support\Autoloader::register(__DIR__ . '/..');

// Environment & Helpers
\App\Support\Env::load(__DIR__ . '/../.env');
require_once __DIR__ . '/../app/Support/helpers.php';

// Session Initialization
\App\Support\Session::start();

// Request & Routing
$request = new \App\Support\Request();
$router = new \App\Support\Router();

$routes = require __DIR__ . '/../config/routes.php';
$routes($router);

try {
    $router->dispatch($request);
} catch (\Throwable $e) {
    error_log(sprintf(
        "[%s] Welkar Unhandled Exception: %s in %s:%d\nStack Trace:\n%s",
        date('Y-m-d H:i:s'),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    ));

    http_response_code(500);

    if (config('app.debug', false)) {
        echo "<div style='font-family:sans-serif; padding:30px; background:#fff3f3; color:#b91c1c;'>";
        echo "<h2>Application Error</h2>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre style='background:#fff; padding:15px; border:1px solid #fca5a5; overflow-x:auto;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        echo "</div>";
    } else {
        $errorView = __DIR__ . '/../resources/views/errors/500.php';
        if (file_exists($errorView)) {
            include $errorView;
        } else {
            echo "<h3>An unexpected error occurred. Please contact Welkar customer support at +91 98200 12345.</h3>";
        }
    }
}
