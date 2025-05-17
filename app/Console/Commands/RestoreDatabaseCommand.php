<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use ZipArchive;

class RestoreDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:restore-database
                            {--backup= : Path to the backup file}
                            {--password= : Password for decrypting the backup}
                            {--force : Force restore without confirmation}
                            {--interactive : Run in interactive mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore database from a secure backup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database restore process...');

        try {
            // Check if we're in interactive mode
            $interactive = $this->option('interactive');

            // Get backup file path
            $backupFile = $this->option('backup');

            // If no backup file specified or in interactive mode, show a list of available backups
            if (empty($backupFile) || $interactive) {
                $backupFile = $this->selectBackupFile();
                if (!$backupFile) {
                    $this->info('Restore cancelled.');
                    return Command::SUCCESS;
                }
            }

            // Check if the backup file exists
            if (!File::exists($backupFile)) {
                $this->error('Backup file not found: ' . $backupFile);
                return Command::FAILURE;
            }

            // Get password
            $password = $this->option('password');
            if (empty($password)) {
                $defaultPassword = env('BACKUP_PASSWORD', 'C1sc0123');
                $password = $this->secret('Enter the backup password:');
                if (empty($password)) {
                    $this->info('Using default password from environment.');
                    $password = $defaultPassword;
                }
            }

            // Confirm restore unless --force is used
            if (!$this->option('force') && !$interactive) {
                $this->warn('WARNING: This will overwrite your current database!');
                $this->warn('Make sure you have a backup of your current data before proceeding.');

                if (!$this->confirm('Do you wish to continue?', false)) {
                    $this->info('Restore cancelled.');
                    return Command::SUCCESS;
                }
            }

            // Create temporary directory
            $tempDir = storage_path('app/restore-temp');
            if (File::exists($tempDir)) {
                File::deleteDirectory($tempDir);
            }
            File::makeDirectory($tempDir, 0755, true);

            // Extract backup
            $this->info('Extracting backup file...');
            $zip = new ZipArchive();
            $result = $zip->open($backupFile);

            if ($result !== true) {
                $this->error('Failed to open backup file. Error code: ' . $result);
                return Command::FAILURE;
            }

            $zip->extractTo($tempDir);
            $zip->close();

            // Check for required files
            $metadataFile = $tempDir . '/backup-metadata.json';
            if (File::exists($metadataFile)) {
                // Read metadata
                $metadata = json_decode(File::get($metadataFile), true);
                if ($metadata) {
                    $this->info('Backup information:');
                    $this->info('- Created: ' . Carbon::parse($metadata['timestamp'])->format('Y-m-d H:i:s'));
                    $this->info('- Database: ' . $metadata['database']);
                    $this->info('- Version: ' . ($metadata['version'] ?? 'unknown'));
                }
            }

            // Find encrypted SQL file
            $encryptedFile = null;
            $files = File::files($tempDir);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'enc') {
                    $encryptedFile = $file;
                    break;
                }
            }

            if (!$encryptedFile) {
                $this->error('Invalid backup: Encrypted SQL file not found.');
                return Command::FAILURE;
            }

            // Verify password hash if available
            if (isset($metadata['password_hash'])) {
                $this->info('Verifying password...');
                $passwordHash = hash('sha256', $password);
                if ($passwordHash !== $metadata['password_hash']) {
                    $this->error('Password verification failed: Incorrect password.');
                    return Command::FAILURE;
                }
                $this->info('Password verified.');
            }

            // Decrypt the SQL file
            $this->info('Decrypting database file...');
            try {
                $decryptedContent = $this->decryptContent(File::get($encryptedFile), $password);
                $sqlFile = $tempDir . '/' . pathinfo($encryptedFile, PATHINFO_FILENAME) . '.sql';
                File::put($sqlFile, $decryptedContent);
            } catch (\Exception $e) {
                $this->error('Failed to decrypt the backup: ' . $e->getMessage());
                $this->error('This may be due to an incorrect password.');
                return Command::FAILURE;
            }

            // Verify hash if available
            if (isset($metadata['hash']) && isset($metadata['hash_algorithm'])) {
                $this->info('Verifying backup integrity...');
                $fileHash = hash_file($metadata['hash_algorithm'], $sqlFile);
                if ($fileHash !== $metadata['hash']) {
                    $this->error('Backup integrity check failed: checksums do not match.');
                    $this->error('The backup may be corrupted or the password may be incorrect.');
                    return Command::FAILURE;
                }
                $this->info('Backup integrity verified.');
            }

            // Get database configuration
            $connection = config('database.default');
            $config = config("database.connections.{$connection}");

            if (!isset($config['dump']['dump_binary_path'])) {
                $this->error('MySQL binary path not configured in config/database.php');
                return Command::FAILURE;
            }

            // Build mysql restore command
            $restoreCommand = sprintf(
                '"%s%s" --user="%s" --password="%s" --host="%s" --port="%s" "%s" < "%s"',
                $config['dump']['dump_binary_path'],
                'mysql',
                $config['username'],
                $config['password'],
                $config['host'],
                $config['port'],
                $config['database'],
                $sqlFile
            );

            $this->info('Restoring database...');

            // Execute mysql restore command
            $returnVar = null;
            $output = [];
            exec($restoreCommand, $output, $returnVar);

            if ($returnVar !== 0) {
                $this->error('Database restore failed. Error code: ' . $returnVar);
                if (!empty($output)) {
                    $this->error('Error output: ' . implode("\n", $output));
                }
                return Command::FAILURE;
            }

            $this->info('Database restored successfully!');

            // Clean up temporary files
            File::deleteDirectory($tempDir);

            // Send email notification for successful restore
            $this->sendEmailNotification(true, $backupFile);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Restore failed: ' . $e->getMessage());

            // Send email notification for failed restore
            $this->sendEmailNotification(false, $backupFile, $e->getMessage());

            return Command::FAILURE;
        }
    }

    /**
     * Show a list of available backups and let the user select one
     */
    protected function selectBackupFile()
    {
        $backupDir = storage_path('app/Laravel');
        if (!File::exists($backupDir)) {
            $this->error('No backups found.');
            return null;
        }

        $backups = collect(File::files($backupDir))
            ->filter(function ($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'zip';
            })
            ->map(function ($file) {
                return [
                    'path' => $file,
                    'filename' => basename($file),
                    'size' => File::size($file),
                    'date' => File::lastModified($file),
                ];
            })
            ->sortByDesc('date')
            ->values()
            ->all();

        if (empty($backups)) {
            $this->error('No backups found.');
            return null;
        }

        $this->info('Available backups:');
        foreach ($backups as $index => $backup) {
            $this->info(sprintf(
                '[%d] %s (%s) - %s',
                $index + 1,
                $backup['filename'],
                $this->formatBytes($backup['size']),
                Carbon::createFromTimestamp($backup['date'])->format('Y-m-d H:i:s')
            ));
        }

        $choice = $this->ask('Select a backup to restore (or 0 to cancel)');
        if (!is_numeric($choice) || $choice < 0 || $choice > count($backups)) {
            $this->error('Invalid selection.');
            return null;
        }

        if ($choice == 0) {
            return null;
        }

        $selectedBackup = $backups[$choice - 1];

        // Confirm restore
        $this->warn('WARNING: This will overwrite your current database!');
        $this->warn('Make sure you have a backup of your current data before proceeding.');

        if (!$this->confirm('Do you wish to continue with restoring ' . $selectedBackup['filename'] . '?', false)) {
            return null;
        }

        return $selectedBackup['path'];
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

    /**
     * Decrypt content with password
     */
    protected function decryptContent($content, $password)
    {
        $data = json_decode(base64_decode($content), true);

        if (!$data || !isset($data['iv']) || !isset($data['data']) || !isset($data['method'])) {
            throw new \Exception('Invalid encrypted file format');
        }

        $iv = base64_decode($data['iv']);

        // Check if we have a salt (for newer backups)
        if (isset($data['salt'])) {
            $salt = $data['salt'];
            $saltedPassword = $password . $salt;
            $key = hash('sha256', $saltedPassword, true);
        } else {
            // Fallback for older backups
            $key = hash('sha256', $password, true);
        }

        $decrypted = openssl_decrypt($data['data'], $data['method'], $key, 0, $iv);

        if ($decrypted === false) {
            throw new \Exception('Decryption failed - incorrect password');
        }

        return $decrypted;
    }

    /**
     * Send email notification about restore status
     */
    protected function sendEmailNotification(bool $success, string $backupFile, string $message = '')
    {
        $adminEmail = env('ADMIN_EMAIL');

        if (empty($adminEmail)) {
            $this->warn('Admin email not configured. Email notification not sent.');
            return;
        }

        $subject = $success ?
            'Database Restore Completed Successfully' :
            'Database Restore Failed';

        // Ensure we use Manila timezone for the timestamp
        $timestamp = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');

        $content = $success ?
            "A database restore was completed successfully at " . $timestamp . " (Manila time).\n\n" .
            "Backup file: " . basename($backupFile) . "\n\n" .
            "This is an automated notification." :
            "A database restore failed at " . $timestamp . " (Manila time).\n\n" .
            "Backup file: " . basename($backupFile) . "\n\n" .
            "Error: " . $message . "\n\n" .
            "Please check the system and resolve the issue.";

        try {
            Mail::raw($content, function ($mail) use ($adminEmail, $subject) {
                $mail->to($adminEmail)
                    ->subject($subject);
            });

            $this->info('Email notification sent to: ' . $adminEmail);
        } catch (\Exception $e) {
            $this->warn('Failed to send email notification: ' . $e->getMessage());
        }
    }
}
