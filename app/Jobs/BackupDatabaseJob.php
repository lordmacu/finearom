<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Symfony\Component\Process\Process;

class BackupDatabaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Handle the job.
     */
    public function handle()
    {
      
        // Ruta para almacenar el backup en storage/app/backups
        $backupPath = storage_path('app/backups/');
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        // Crear el nombre del archivo de backup con timestamp
        $fileName = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';

        dd([
            'mysqldump',
            '--user=' . env('DB_USERNAME'),
            '--password=' . env('DB_PASSWORD'),
            '--host=' . env('DB_HOST'),
            env('DB_DATABASE'),
            '--result-file=' . $backupPath . $fileName,
        ]);
        // Comando para generar el backup
        $process = new Process([
            'mysqldump',
            '--user=' . env('DB_USERNAME'),
            '--password=' . env('DB_PASSWORD'),
            '--host=' . env('DB_HOST'),
            env('DB_DATABASE'),
            '--result-file=' . $backupPath . $fileName,
        ]);

        $process->run();

        if ($process->isSuccessful()) {
            echo "Backup completed successfully";
        } else {
            echo "Backup failed";
        }

        // Eliminar archivos viejos
        $this->deleteOldBackups($backupPath);
    }

    /**
     * Elimina los backups que tienen más de una semana.
     */
    private function deleteOldBackups($backupPath)
    {
        $files = glob($backupPath . '*.sql');

        foreach ($files as $file) {
            if (filemtime($file) < Carbon::now()->subWeek()->timestamp) {
                unlink($file);
            }
        }
    }
}
