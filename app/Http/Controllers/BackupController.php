<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::where('empresa_id', auth()->user()->empresa_id)
            ->with('user')->latest()->paginate(20);
        return view('sistema.backup.index', compact('backups'));
    }

    public function crear(Request $request)
    {
        $request->validate([
            'descripcion' => 'nullable|string|max:255',
        ]);

        try {
            $config = config('database.connections.mysql');
            $timestamp = now()->format('Y_m_d_His');
            $filename = "backup_tpv_{$timestamp}.sql";
            $path = storage_path('app/backups');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }
            $fullPath = $path . DIRECTORY_SEPARATOR . $filename;

            // Generar el dump usando consultas directas (no requiere mysqldump CLI)
            $sql = $this->generarDump($config['database']);
            File::put($fullPath, $sql);

            $tamano = File::size($fullPath);

            $backup = Backup::create([
                'empresa_id' => auth()->user()->empresa_id,
                'user_id' => auth()->id(),
                'nombre' => $filename,
                'archivo' => 'backups/' . $filename,
                'tamano' => $tamano,
                'tipo' => 'manual',
                'descripcion' => $request->input('descripcion'),
            ]);

            return back()->with('success', "Copia de seguridad creada: {$filename} ({$backup->tamano_formateado})");
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al crear copia: ' . $e->getMessage());
        }
    }

    public function descargar(Backup $backup)
    {
        abort_if($backup->empresa_id !== auth()->user()->empresa_id, 403);
        $path = storage_path('app/' . $backup->archivo);
        if (!file_exists($path)) {
            return back()->with('error', 'El archivo de copia no existe.');
        }
        return response()->download($path, $backup->nombre);
    }

    public function restaurar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:sql,txt|max:51200',
            'confirmar' => 'required|in:CONFIRMAR',
        ]);

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $sql = file_get_contents($request->file('archivo')->getRealPath());
            DB::unprepared($sql);

            return back()->with('success', 'Copia de seguridad restaurada correctamente. Cierra sesión y vuelve a entrar.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al restaurar: ' . $e->getMessage());
        } finally {
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } catch (\Throwable $e) {
                // Nada: si falla aquí, la conexión se descartará al final de la petición.
            }
        }
    }

    public function destroy(Backup $backup)
    {
        abort_if($backup->empresa_id !== auth()->user()->empresa_id, 403);
        $path = storage_path('app/' . $backup->archivo);
        if (file_exists($path)) {
            unlink($path);
        }
        $backup->delete();
        return back()->with('success', 'Copia eliminada.');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'confirmar' => 'required|in:RESETEAR',
            'mantener_empresa' => 'nullable|boolean',
        ]);

        try {
            $tablasOperativas = [
                'detalle_ventas', 'pagos_ventas', 'ventas',
                'movimientos_caja', 'cajas',
                'cita_servicios', 'citas',
                'uso_bonos', 'bonos', 'tipos_bonos',
                'movimientos_stock', 'productos', 'categorias_productos',
                'servicios', 'categorias_servicios',
                'clientes',
                'logs_actividad', 'backups',
            ];

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            foreach ($tablasOperativas as $tabla) {
                if (Schema::hasTable($tabla)) {
                    DB::table($tabla)->truncate();
                }
            }
            if (!$request->boolean('mantener_empresa')) {
                DB::table('empleados')->truncate();
                DB::table('configuraciones')->truncate();
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return back()->with('success', 'Sistema reseteado. Todos los datos operativos han sido eliminados.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al resetear: ' . $e->getMessage());
        } finally {
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } catch (\Throwable $e) {
                // Ignorar: se restablecerá con una nueva conexión.
            }
        }
    }

    private function generarDump(string $database): string
    {
        $sql = "-- TPV Peluquería - Backup generado el " . now()->format('Y-m-d H:i:s') . "\n";
        $sql .= "-- Base de datos: {$database}\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $sql .= "SET NAMES utf8mb4;\n\n";

        $tablas = DB::select('SHOW TABLES');
        $columnaTablas = "Tables_in_{$database}";

        foreach ($tablas as $t) {
            $tabla = $t->{$columnaTablas};
            $createTable = DB::select("SHOW CREATE TABLE `{$tabla}`");
            $createSql = $createTable[0]->{'Create Table'};

            $sql .= "\n-- Tabla: {$tabla}\n";
            $sql .= "DROP TABLE IF EXISTS `{$tabla}`;\n";
            $sql .= $createSql . ";\n\n";

            $rows = DB::table($tabla)->get();
            if ($rows->isEmpty()) continue;

            $columnas = array_keys((array) $rows->first());
            $columnasSql = '`' . implode('`,`', $columnas) . '`';

            foreach ($rows as $row) {
                $valores = [];
                foreach ((array) $row as $val) {
                    if ($val === null) {
                        $valores[] = 'NULL';
                    } elseif (is_numeric($val) && !str_starts_with((string) $val, '0') || $val === '0') {
                        $valores[] = $val;
                    } else {
                        $valores[] = "'" . str_replace(["'", "\n", "\r"], ["''", '\\n', '\\r'], (string) $val) . "'";
                    }
                }
                $sql .= "INSERT INTO `{$tabla}` ({$columnasSql}) VALUES (" . implode(',', $valores) . ");\n";
            }
        }

        $sql .= "\nSET FOREIGN_KEY_CHECKS=1;\n";
        return $sql;
    }
}
