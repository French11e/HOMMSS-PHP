<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportDataForSeeding extends Command
{
    protected $signature = 'app:export-data {--with-images : Also backup image files}';
    protected $description = 'Export current database data for seeding';

    public function handle()
    {
        // Export database data to JSON
        $this->info('Exporting database data...');
        $brands = Brand::all()->toArray();
        $categories = Category::all()->toArray();
        $products = Product::all()->toArray();

        $path = database_path('seeders/data');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        File::put("$path/brands.json", json_encode($brands, JSON_PRETTY_PRINT));
        File::put("$path/categories.json", json_encode($categories, JSON_PRETTY_PRINT));
        File::put("$path/products.json", json_encode($products, JSON_PRETTY_PRINT));

        $this->info('Data exported successfully!');

        // Backup image files if requested
        if ($this->option('with-images')) {
            $this->backupImages();
        } else {
            // Remind about image files
            $this->warn('Remember to copy image files from:');
            $this->line('- public/uploads/brands');
            $this->line('- public/uploads/categories');
            $this->line('- public/uploads/products');
            $this->line('- public/uploads/products/thumbnails');
            $this->line('');
            $this->line('To include images in backup, run: php artisan app:export-data --with-images');
        }
    }

    protected function backupImages()
    {
        $this->info('Backing up image files...');
        
        // Create backup directory
        $backupPath = storage_path('app/backups/images');
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true, true);
        }
        
        // Copy image directories
        $this->copyDirectory(public_path('uploads/brands'), "$backupPath/brands");
        $this->copyDirectory(public_path('uploads/categories'), "$backupPath/categories");
        $this->copyDirectory(public_path('uploads/products'), "$backupPath/products");
        
        $this->info('Images backed up to: ' . $backupPath);
    }

    protected function copyDirectory($source, $destination)
    {
        if (!File::exists($source)) {
            $this->warn("Source directory not found: $source");
            return;
        }

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }
        
        // Copy files
        $files = File::files($source);
        $count = 0;
        foreach ($files as $file) {
            if (File::basename($file) != '.gitkeep') {
                File::copy($file, $destination . '/' . File::basename($file));
                $count++;
            }
        }
        
        $this->line("Copied $count files from " . File::basename($source));
        
        // Handle subdirectories (like thumbnails)
        $directories = File::directories($source);
        foreach ($directories as $directory) {
            $this->copyDirectory(
                $directory, 
                $destination . '/' . File::basename($directory)
            );
        }
    }
}

