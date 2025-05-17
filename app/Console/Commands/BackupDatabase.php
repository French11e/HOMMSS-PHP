<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-database
                            {--only-db : Backup only the database}
                            {--filename= : Custom filename for the backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new backup of the database using Spatie Backup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting backup process...');

        try {
            $onlyDb = $this->option('only-db');
            $customFilename = $this->option('filename');

            // Build the command arguments
            $arguments = [];

            if ($onlyDb) {
                $this->info('Backing up only the database...');
                $arguments['--only-db'] = true;
            } else {
                $this->info('Backing up database and files...');
            }

            // Set custom filename if provided
            if ($customFilename) {
                $arguments['--filename'] = $customFilename;
            } else {
                $prefix = $onlyDb ? 'db-' : 'full-';
                $arguments['--filename'] = $prefix . Carbon::now()->format('Y-m-d-H-i-s');
            }

            // Call the Spatie backup command
            $this->call('backup:run', $arguments);

            $this->info('Backup completed successfully!');

            // Show backup information
            $this->displayBackupInfo();

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Display information about available backups
     */
    protected function displayBackupInfo()
    {
        $this->info('');
        $this->info('Backup Information:');
        $this->info('------------------');
        $this->info('Backups are stored in: ' . storage_path('app/private/backups'));
        $this->info('Backups are encrypted with password from BACKUP_ARCHIVE_PASSWORD env variable');
        $this->info('');

        // List recent backups
        $this->info('Recent backups:');
        $backups = collect(Storage::disk('local')->files('backups'))
            ->filter(function ($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'zip';
            })
            ->map(function ($file) {
                return [
                    'file' => $file,
                    'size' => Storage::disk('local')->size($file),
                    'date' => Storage::disk('local')->lastModified($file),
                ];
            })
            ->sortByDesc('date')
            ->take(5);

        if ($backups->isEmpty()) {
            $this->info('No backups found.');
            return;
        }

        $this->table(
            ['Filename', 'Size', 'Date'],
            $backups->map(function ($backup) {
                return [
                    basename($backup['file']),
                    $this->formatBytes($backup['size']),
                    Carbon::createFromTimestamp($backup['date'])->format('Y-m-d H:i:s'),
                ];
            })
        );
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
