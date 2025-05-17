<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use ZipArchive;

class BackupDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-database
                            {--filename= : Custom filename for the backup}
                            {--password= : Password for encrypting the backup (will prompt if not provided)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a secure, encrypted backup of the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database backup process...');

        try {
            // Get database configuration
            $connection = config('database.default');
            $config = config("database.connections.{$connection}");

            if (!isset($config['dump']['dump_binary_path'])) {
                $this->error('MySQL dump binary path not configured in config/database.php');
                return Command::FAILURE;
            }

            // Create backup directory if it doesn't exist
            $backupDir = storage_path('app/Laravel');
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            // Create temporary directory
            $tempDir = storage_path('app/backup-temp');
            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0755, true);
            }

            // Set filename
            $customFilename = $this->option('filename');
            $filename = $customFilename ?: 'hommss-db-backup' . Carbon::now()->format('Y-m-d-H-i-s');

            // Get encryption password
            $password = $this->option('password');

            // If no password provided, check if we're running in a console
            if (empty($password)) {
                $defaultPassword = env('BACKUP_PASSWORD', 'C1sc0123');

                // Check if we're running in interactive mode (with a console)
                if ($this->input->isInteractive()) {
                    $password = $this->secret('Enter a password for encrypting the backup (leave empty for default):');
                    if (empty($password)) {
                        $password = $defaultPassword;
                        $this->info('Using default password from environment.');
                    }
                } else {
                    // We're running from scheduler or non-interactive environment
                    $password = $defaultPassword;
                    $this->info('Running in non-interactive mode. Using default password from environment.');
                }
            }

            // SQL file path
            $sqlFile = "{$tempDir}/{$config['database']}.sql";

            // Build mysqldump command
            $dumpCommand = sprintf(
                '"%s%s" --user="%s" --password="%s" --host="%s" --port="%s" "%s" > "%s"',
                $config['dump']['dump_binary_path'],
                'mysqldump',
                $config['username'],
                $config['password'],
                $config['host'],
                $config['port'],
                $config['database'],
                $sqlFile
            );

            $this->info('Dumping database...');

            // Execute mysqldump command
            $returnVar = null;
            $output = [];
            exec($dumpCommand, $output, $returnVar);

            if ($returnVar !== 0) {
                $this->error('Database dump failed. Error code: ' . $returnVar);
                return Command::FAILURE;
            }

            $this->info('Database dumped successfully.');

            // Encrypt the SQL file
            $this->info('Encrypting database dump...');
            $sqlContent = File::get($sqlFile);
            $encryptedContent = $this->encryptContent($sqlContent, $password);
            $encryptedFile = "{$tempDir}/{$config['database']}.enc";
            File::put($encryptedFile, $encryptedContent);

            // Generate hash for verification
            $fileHash = hash_file('sha256', $sqlFile);

            // Generate a password verification hash (different from the encryption key)
            $passwordVerificationHash = hash('sha256', $password);

            // Create metadata file with timestamp, database info, and hash
            $metadata = [
                'timestamp' => Carbon::now()->toIso8601String(),
                'database' => $config['database'],
                'version' => '1.0',
                'encrypted' => true,
                'hash' => $fileHash,
                'hash_algorithm' => 'sha256',
                'password_hash' => $passwordVerificationHash // Store password hash for verification
            ];

            $metadataFile = "{$tempDir}/backup-metadata.json";
            File::put($metadataFile, json_encode($metadata, JSON_PRETTY_PRINT));

            // Create ZIP file
            $zipFile = "{$backupDir}/{$filename}.zip";
            $this->info('Creating ZIP archive: ' . $zipFile);

            $zip = new ZipArchive();
            $result = $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            if ($result !== true) {
                $this->error('Failed to create ZIP file. Error code: ' . $result);
                return Command::FAILURE;
            }

            // Add encrypted SQL file to ZIP
            $zip->addFile($encryptedFile, basename($encryptedFile));
            $zip->setCompressionName(basename($encryptedFile), ZipArchive::CM_STORE, 0);

            // Add metadata file to ZIP
            $zip->addFile($metadataFile, basename($metadataFile));
            $zip->setCompressionName(basename($metadataFile), ZipArchive::CM_STORE, 0);

            // Add README file to ZIP
            $readmeContent = "SECURE DATABASE BACKUP\n\n";
            $readmeContent .= "Created: " . Carbon::now()->format('Y-m-d H:i:s') . "\n";
            $readmeContent .= "Database: " . $config['database'] . "\n";
            $readmeContent .= "Encrypted: Yes\n";
            $readmeContent .= "Hash Algorithm: SHA-256\n\n";
            $readmeContent .= "This backup is encrypted and requires a password to restore.\n\n";
            $readmeContent .= "To restore this backup, use the command:\n";
            $readmeContent .= "php artisan app:restore-database --backup=" . basename($zipFile) . "\n\n";
            $readmeContent .= "Or use the restore-database.bat script.\n";

            $readmeFile = "{$tempDir}/README.txt";
            File::put($readmeFile, $readmeContent);
            $zip->addFile($readmeFile, "README.txt");

            // Close ZIP file
            $zip->close();

            // Delete temporary files
            File::delete($sqlFile);
            File::delete($encryptedFile);
            File::delete($metadataFile);
            File::delete($readmeFile);

            $this->info('Secure backup completed successfully!');
            $this->info('Backup file: ' . $zipFile);
            $this->info('The backup is encrypted and password-protected.');
            $this->info('Keep your backup password safe - you will need it to restore the backup.');

            // Send email notification for successful backup
            $this->sendEmailNotification(true);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            // Send email notification for failed backup
            $this->sendEmailNotification(false, $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Send email notification about backup status
     */
    protected function sendEmailNotification(bool $success, string $message = '')
    {
        // Only send emails for manual backups if not running from scheduler
        if (!$this->laravel->runningInConsole() || $this->input->isInteractive()) {
            $adminEmail = env('ADMIN_EMAIL');

            if (empty($adminEmail)) {
                $this->warn('Admin email not configured. Email notification not sent.');
                return;
            }

            $subject = $success ?
                'Database Backup Completed Successfully' :
                'Database Backup Failed';

            // Ensure we use Manila timezone for the timestamp
            $timestamp = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');

            $content = $success ?
                "A database backup was completed successfully at " . $timestamp . " (Manila time).\n\n" .
                "This is an automated notification." :
                "A database backup failed at " . $timestamp . " (Manila time).\n\n" .
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

    /**
     * Encrypt content with password
     */
    protected function encryptContent($content, $password)
    {
        // Generate a random initialization vector
        $iv = openssl_random_pseudo_bytes(16);

        // Add a salt to the password (using part of the IV as salt)
        $salt = substr(bin2hex($iv), 0, 16);
        $saltedPassword = $password . $salt;

        // Create a key from the salted password
        $key = hash('sha256', $saltedPassword, true);

        // Encrypt the content
        $encrypted = openssl_encrypt($content, 'AES-256-CBC', $key, 0, $iv);

        // Combine the IV, salt, and encrypted content
        return base64_encode(json_encode([
            'iv' => base64_encode($iv),
            'salt' => $salt,
            'data' => $encrypted,
            'method' => 'AES-256-CBC'
        ]));
    }
}
