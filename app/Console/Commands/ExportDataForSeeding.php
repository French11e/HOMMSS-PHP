<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportDataForSeeding extends Command
{
    protected $signature = 'app:export-data';
    protected $description = 'Export current database data for seeding';

    public function handle()
    {
        $brands = Brand::all()->toArray();
        $categories = Category::all()->toArray();
        $products = Product::all()->toArray();

        $data = [
            'brands' => $brands,
            'categories' => $categories,
            'products' => $products,
        ];

        $path = database_path('seeders/data');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        File::put("$path/brands.json", json_encode($brands, JSON_PRETTY_PRINT));
        File::put("$path/categories.json", json_encode($categories, JSON_PRETTY_PRINT));
        File::put("$path/products.json", json_encode($products, JSON_PRETTY_PRINT));

        $this->info('Data exported successfully!');

        // Remind about image files
        $this->warn('Remember to copy image files from:');
        $this->line('- public/uploads/brands');
        $this->line('- public/uploads/categories');
        $this->line('- public/uploads/products');
        $this->line('- public/uploads/products/thumbnails');
    }
}
