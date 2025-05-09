<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/products.json');

        if (File::exists($jsonPath)) {
            $products = json_decode(File::get($jsonPath), true);

            foreach ($products as $product) {
                Product::updateOrCreate(
                    ['slug' => $product['slug']],
                    [
                        'name' => $product['name'],
                        'short_description' => $product['short_description'],
                        'description' => $product['description'],
                        'regular_price' => $product['regular_price'],
                        'sale_price' => $product['sale_price'],
                        'SKU' => $product['SKU'],
                        'stock_status' => $product['stock_status'],
                        'featured' => $product['featured'],
                        'quantity' => $product['quantity'],
                        'image' => $product['image'] ?? null,
                        'images' => $product['images'] ?? null,
                        'category_id' => $product['category_id'],
                        'brand_id' => $product['brand_id'],
                        'created_at' => $product['created_at'],
                        'updated_at' => $product['updated_at'],
                    ]
                );
            }
        }
    }
}
