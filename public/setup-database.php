<?php

require __DIR__ . '/../vendor/autoload.php';

$cacheDir = __DIR__ . '/../storage/framework/cache/data';
if (!is_dir($cacheDir)) {
    @mkdir($cacheDir, 0777, true);
}
if (!is_writable($cacheDir)) {
    @chmod($cacheDir, 0777);
}

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

header('Content-Type: text/html; charset=utf-8');
echo '<!doctype html><html><head><meta charset="utf-8"><title>Configuración de base de datos</title></head><body>';
echo '<h1>Configurando base de datos...</h1>';
echo '<p>Esto ejecutará las migraciones y llenará los datos iniciales.</p>';
echo '<pre>';

$status = $kernel->call('migrate:fresh', ['--seed' => true, '--force' => true]);

$buffer = $kernel->output();
echo $buffer;
echo '</pre>';
echo '<p><strong>Código de salida:</strong> ' . $status . '</p>';
echo '</body></html>';
