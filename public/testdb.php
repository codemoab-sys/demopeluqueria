<?php
header('Content-Type: text/html; charset=utf-8');
echo '<!doctype html><html><head><meta charset="utf-8"></head><body><pre>';

function leerEnv(string $archivo): array
{
    $vars = [];
    if (!file_exists($archivo)) return $vars;
    foreach (file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linea) {
        $linea = trim($linea);
        if ($linea === '' || str_starts_with($linea, '#') || !str_contains($linea, '=')) continue;
        [$k, $v] = explode('=', $linea, 2);
        $v = trim($v);
        if ((str_starts_with($v, '"') && str_ends_with($v, '"')) || (str_starts_with($v, "'") && str_ends_with($v, "'"))) {
            $v = substr($v, 1, -1);
        }
        $vars[trim($k)] = $v;
    }
    return $vars;
}

$env = leerEnv(__DIR__ . '/../.env');

$host     = $env['DB_HOST'] ?? getenv('DB_HOST') ?: '(no definido)';
$port     = $env['DB_PORT'] ?? '3306';
$database = $env['DB_DATABASE'] ?? '(no definido)';
$user     = $env['DB_USERNAME'] ?? '(no definido)';
$pass     = $env['DB_PASSWORD'] ?? '';
$prefix   = $env['DB_TABLE_PREFIX'] ?? '(no definido)';

echo "Valores leídos del .env:\n";
echo "  DB_HOST         = {$host}\n";
echo "  DB_PORT         = {$port}\n";
echo "  DB_DATABASE     = {$database}\n";
echo "  DB_USERNAME     = {$user}\n";
echo "  DB_PASSWORD     = " . (strlen($pass) ? '***** (' . strlen($pass) . ' caracteres)' : '(vacía)') . "\n";
echo "  DB_TABLE_PREFIX = {$prefix}\n\n";

if ($host === '(no definido)') {
    echo "ERROR: no se pudo leer el .env. ¿Está en la raíz del proyecto?\n";
    exit;
}

$conn = @mysqli_connect($host, $user, $pass, $database, (int) $port);
if (!$conn) {
    echo "FALLO DE CONEXIÓN (mysqli):\n";
    echo "  " . mysqli_connect_errno() . ": " . mysqli_connect_error() . "\n\n";
    echo "Posibles causas:\n";
    echo "  - La contraseña del usuario no coincide con la del hosting.\n";
    echo "  - El usuario '{$user}' NO está agregado a la base '{$database}' en cPanel (MySQL Databases > Add User to Database) con ALL PRIVILEGES.\n";
    echo "  - El DB_HOST no es localhost (revisa en cPanel el host de MySQL de tu hosting).\n";
    exit;
}

echo "CONEXIÓN CORRECTA ✓\n\n";

$res = mysqli_query($conn, 'SHOW TABLES');
$tablas = [];
if ($res) {
    while ($row = mysqli_fetch_row($res)) {
        $tablas[] = $row[0];
    }
}
echo 'Tablas en la BD (' . count($tablas) . "):\n";
echo '  ' . implode(', ', $tablas) . "\n\n";

$prefijadas = array_filter($tablas, fn($t) => str_starts_with($t, 'pelu_'));
echo 'Tablas con prefijo pelu_: ' . count($prefijadas) . "\n";
if (count($prefijadas) === 0) {
    echo "  ⚠️ Ninguna tabla con prefijo. La BD está vacía o sin importar.\n";
    echo "  → Importa bk_basededatos.sql o crea las tablas con migraciones.\n";
} else {
    echo '  ✓ Ejemplos: ' . implode(', ', array_slice(array_values($prefijadas), 0, 8)) . "\n";
}

$grants = mysqli_query($conn, "SHOW GRANTS FOR CURRENT_USER");
if ($grants) {
    echo "\nPermisos del usuario actual:\n";
    while ($row = mysqli_fetch_row($grants)) {
        echo '  ' . $row[0] . "\n";
    }
}

mysqli_close($conn);
echo "\nHecho. Borra este archivo del hosting cuando termines.\n";
echo '</pre></body></html>';
