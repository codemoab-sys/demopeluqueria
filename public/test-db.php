<?php
header('Content-Type: text/html; charset=utf-8');

echo '<!doctype html><html><head><meta charset="utf-8"><title>Prueba de base de datos</title></head><body>';
echo '<h1>Prueba de conexión MySQL</h1>'; 
echo '<pre>';

$basePath = dirname(__DIR__);
$envPath = $basePath . '/.env';
$env = [];
if (is_file($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
        $env[trim($key)] = trim($value, " \t\r\n\"'");
    }
}

$host = $env['DB_HOST'] ?? 'localhost';
$port = $env['DB_PORT'] ?? '3306';
$database = $env['DB_DATABASE'] ?? '';
$username = $env['DB_USERNAME'] ?? '';
$password = $env['DB_PASSWORD'] ?? '';
$prefix = $env['DB_TABLE_PREFIX'] ?? '';

echo 'Host: ' . $host . PHP_EOL;
echo 'Port: ' . $port . PHP_EOL;
echo 'Base de datos: ' . $database . PHP_EOL;
echo 'Usuario: ' . $username . PHP_EOL;
echo 'Prefijo de tablas: ' . ($prefix !== '' ? $prefix : '(sin prefijo)') . PHP_EOL;

echo '---' . PHP_EOL;

try {
    $dsn = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database;
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "Conexión OK\n";
} catch (Throwable $e) {
    echo 'Error de conexión: ' . $e->getMessage() . PHP_EOL;
}

echo '</pre>';
echo '<p>Si el usuario falla, debes corregir las credenciales en .env o crear el usuario en el panel del hosting.</p>';
echo '</body></html>';
