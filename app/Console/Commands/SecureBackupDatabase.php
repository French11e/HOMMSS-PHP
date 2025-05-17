<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use ZipArchive;

class SecureBackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:secure-backup-database 
                            {--filename= : Custom filename for the backup}
                            {--password= : Password for encrypting the backup (defaults to env BACKUP_PASSWORD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a secure encrypted backup of the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting secure database backup process...');

        try {
            // Get database configuration
            $connection = config('database.default');
            $config = config("database.connections.{$connection}");
            
            if (!isset($config['dump']['dump_binary_path'])) {
                $this->error('MySQL dump binary path not configured in config/database.php');
                return Command::FAILURE;
            }
            
            // Create backup directory if it doesn't exist
            $backupDir = storage_path('app/private/backups');
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
            $filename = $customFilename ?: 'secure-db-backup-' . Carbon::now()->format('Y-m-d-H-i-s');
            
            // Get encryption password
            $password = $this->option('password') ?: env('BACKUP_PASSWORD', 'C1sc0123');
            
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
            $encryptedContent = $this->encryptFile($sqlFile, $password);
            $encryptedFile = "{$tempDir}/{$config['database']}.enc";
            File::put($encryptedFile, $encryptedContent);
            
            // Create metadata file with timestamp and checksum
            $metadata = [
                'timestamp' => Carbon::now()->toIso8601String(),
                'database' => $config['database'],
                'checksum' => md5_file($sqlFile),
                'encrypted' => true,
                'version' => '1.0'
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
            $readmeContent .= "Database: " . $config['database'] . "\n\n";
            $readmeContent .= "This backup is encrypted and requires a password to restore.\n";
            $readmeContent .= "Use the secure-restore-database command to restore this backup.\n";
            
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
            $this->info('Password: ' . $password);
            $this->info('Keep this password safe - you will need it to restore the backup.');
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
    
    /**
     * Encrypt file content with password
     */
    protected function encryptFile($filePath, $password)
    {
        $content = File::get($filePath);
        
        // Use Laravel's encryption with the password as the key
        // We'll use a simple encryption method that can be decrypted later
        $iv = openssl_random_pseudo_bytes(16);
        $key = hash('sha256', $password, true);
        $encrypted = openssl_encrypt($content, 'AES-256-CBC', $key, 0, $iv);
        
        // Prepend the IV to the encrypted content
        return base64_encode(json_encode([
            'iv' => base64_encode($iv),
            'data' => $encrypted,
            'method' => 'AES-256-CBC'
        ]));
    }
}
