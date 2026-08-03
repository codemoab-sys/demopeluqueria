<?php

header('Content-Type: text/html; charset=utf-8');

echo '<!doctype html><html><head><meta charset="utf-8"><title>Configuración de base de datos</title></head><body>';
echo '<h1>Configurando base de datos...</h1>';
echo '<p>Esto ejecutará las migraciones y llenará los datos iniciales.</p>';
echo '<pre>';

$basePath = dirname(__DIR__);
$artisan = $basePath . DIRECTORY_SEPARATOR . 'artisan';
$phpBinary = escapeshellarg(PHP_BINARY);
$artisanArg = escapeshellarg($artisan);
$command = $phpBinary . ' ' . $artisanArg . ' migrate:fresh --seed --force 2>&1';

if (function_exists('proc_open')) {
    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    $process = proc_open($command, $descriptors, $pipes, $basePath);
    if (is_resource($process)) {
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $status = proc_close($process);
        $output = trim($stdout . PHP_EOL . $stderr);
        echo htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
        echo '</pre>';
        echo '<p><strong>Código de salida:</strong> ' . $status . '</p>';
        echo '</body></html>';
        exit;
    }
}

echo 'No se pudo ejecutar el proceso de migración desde PHP.';
echo '</pre>';
echo '<p><strong>Verifica que el servidor tenga habilitado proc_open o shell_exec.</strong></p>';
echo '</body></html>';
