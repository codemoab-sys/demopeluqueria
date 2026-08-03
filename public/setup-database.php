<?php

header('Content-Type: text/html; charset=utf-8');

$basePath = dirname(__DIR__);
$cacheDirs = [
    $basePath . '/storage/framework/cache/data',
    $basePath . '/storage/framework/views',
    $basePath . '/storage/framework/sessions',
    $basePath . '/storage/framework/testing',
];

foreach ($cacheDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    if (is_dir($dir) && !is_writable($dir)) {
        @chmod($dir, 0777);
    }
}

putenv('CACHE_DRIVER=array');
$_ENV['CACHE_DRIVER'] = 'array';
$_SERVER['CACHE_DRIVER'] = 'array';

require $basePath . '/vendor/autoload.php';
$app = require $basePath . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo '<!doctype html><html><head><meta charset="utf-8"><title>Configuración de base de datos</title></head><body>';
echo '<h1>Configurando base de datos...</h1>';
echo '<p>Esto ejecutará las migraciones y llenará los datos iniciales.</p>';
echo '<pre>';

try {
    ob_start();
    $status = $kernel->call('migrate:fresh', ['--seed' => true, '--force' => true]);
    $output = ob_get_clean();
    echo htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
} catch (Throwable $e) {
    $output = $e->getMessage() . PHP_EOL . $e->getTraceAsString();
    echo htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
    $status = 1;
}

echo '</pre>';
echo '<p><strong>Código de salida:</strong> ' . $status . '</p>';
echo '</body></html>';
