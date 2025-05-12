<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // Call the demo seeder
        $this->call([
            DemoSeeder::class,
            // Add other seeders here
        ]);
    }
}

