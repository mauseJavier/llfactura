<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;


class ExportDatabase extends Command
{
    protected $signature = 'db:export';
    protected $description = 'Export the database to a .sql file';

    public function handle()
    {
        // Configuración de conexión
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');

        $fileName = 'backup-' . now()->format('Y-m-d_H-i-s') . '.sql';

        // dd(

        //     $database,
        //     $username,
        //     $password,
        //     $host,
        //     $port,
        // );

        // Ruta del archivo
        $filePath = storage_path('app/' . $fileName);

        // Comando mysqldump
        $command = "mysqldump --user={$username} --password={$password} --host={$host}:{$port} {$database} > {$filePath}";

        $command ="docker exec -it mysql mysqldump -u {$username} -p {$database} > {$filePath}";


        // dd($command);

        // Ejecutar el comando
        $result = null;
        system($command, $result);

        // Verificar resultado
        if ($result === 0) {
            $this->info("Backup creado: {$filePath}");
        } else {
            $this->error('Error al crear el backup. Verifica las credenciales de la base de datos y mysqldump.');
        }

        return $result;
    }
}
