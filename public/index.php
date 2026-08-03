<?php

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
if (preg_match('#(^|/)(public/)?(setup-database|test-db)\.php$#', $requestUri)) {
    require __DIR__ . '/' . basename($requestUri);
    exit;
}

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
