<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing brands from your current database
        $brands = Brand::all()->toArray();

        // If you want to manually define brands instead, use this format:
        /*
        $brands = [
            [
                'name' => 'Brand Name 1',
                'slug' => 'brand-name-1',
                'image' => '1234567890.jpg', // Make sure to copy these images to the uploads/brands folder
            ],
            [
                'name' => 'Brand Name 2',
                'slug' => 'brand-name-2',
                'image' => '1234567891.jpg',
            ],
        ];
        */

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['slug' => $brand['slug']],
                [
                    'name' => $brand['name'],
                    'image' => $brand['image'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
