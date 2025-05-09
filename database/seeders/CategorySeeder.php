<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/categories.json');

        if (File::exists($jsonPath)) {
            $categories = json_decode(File::get($jsonPath), true);

            foreach ($categories as $category) {
                Category::updateOrCreate(
                    ['slug' => $category['slug']],
                    [
                        'name' => $category['name'],
                        'image' => $category['image'] ?? null,
                        'parent_id' => $category['parent_id'] ?? null,
                        'created_at' => $category['created_at'],
                        'updated_at' => $category['updated_at'],
                    ]
                );
            }
        }
    }
}
