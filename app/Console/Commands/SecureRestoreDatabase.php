<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use ZipArchive;

class SecureRestoreDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:secure-restore-database 
                            {backup : Path to the backup file}
                            {--password= : Password for decrypting the backup}
                            {--force : Force restore without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore a secure encrypted database backup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting secure database restore process...');

        try {
            // Get backup file path
            $backupFile = $this->argument('backup');
            
            // Check if the backup file exists
            if (!File::exists($backupFile)) {
                $this->error('Backup file not found: ' . $backupFile);
                return Command::FAILURE;
            }
            
            // Get password
            $password = $this->option('password');
            if (empty($password)) {
                $password = $this->secret('Enter the backup password:');
                if (empty($password)) {
                    $this->error('Password is required to decrypt the backup.');
                    return Command::FAILURE;
                }
            }
            
            // Confirm restore unless --force is used
            if (!$this->option('force')) {
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
            if (!File::exists($metadataFile)) {
                $this->error('Invalid backup: metadata file not found.');
                return Command::FAILURE;
            }
            
            // Read metadata
            $metadata = json_decode(File::get($metadataFile), true);
            if (!$metadata) {
                $this->error('Invalid backup: metadata file is corrupted.');
                return Command::FAILURE;
            }
            
            $this->info('Backup information:');
            $this->info('- Created: ' . Carbon::parse($metadata['timestamp'])->format('Y-m-d H:i:s'));
            $this->info('- Database: ' . $metadata['database']);
            $this->info('- Version: ' . ($metadata['version'] ?? 'unknown'));
            
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
                $this->error('Invalid backup: encrypted database file not found.');
                return Command::FAILURE;
            }
            
            // Decrypt the SQL file
            $this->info('Decrypting database file...');
            try {
                $decryptedContent = $this->decryptFile($encryptedFile, $password);
                $sqlFile = $tempDir . '/' . pathinfo($encryptedFile, PATHINFO_FILENAME) . '.sql';
                File::put($sqlFile, $decryptedContent);
            } catch (\Exception $e) {
                $this->error('Failed to decrypt the backup: ' . $e->getMessage());
                $this->error('This may be due to an incorrect password.');
                return Command::FAILURE;
            }
            
            // Verify checksum if available
            if (isset($metadata['checksum'])) {
                $this->info('Verifying backup integrity...');
                $checksum = md5_file($sqlFile);
                if ($checksum !== $metadata['checksum']) {
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
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Restore failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
    
    /**
     * Decrypt file content with password
     */
    protected function decryptFile($filePath, $password)
    {
        $content = File::get($filePath);
        $data = json_decode(base64_decode($content), true);
        
        if (!$data || !isset($data['iv']) || !isset($data['data']) || !isset($data['method'])) {
            throw new \Exception('Invalid encrypted file format');
        }
        
        $iv = base64_decode($data['iv']);
        $key = hash('sha256', $password, true);
        $decrypted = openssl_decrypt($data['data'], $data['method'], $key, 0, $iv);
        
        if ($decrypted === false) {
            throw new \Exception('Decryption failed - incorrect password');
        }
        
        return $decrypted;
    }
}
