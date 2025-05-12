<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo admin account
        User::create([
            'name' => 'Demo Admin',
            'email' => 'admin@demo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('demo1234'),
            'remember_token' => Str::random(10),
            'utype' => 'ADM', // Using your existing user type field
        ]);

        // Create demo customer account
        User::create([
            'name' => 'Demo Customer',
            'email' => 'customer@demo.com',
            'email_verified_at' => now(),
            'password' => Hash::make('demo1234'),
            'remember_token' => Str::random(10),
            'utype' => 'USR',
        ]);

        $this->command->info('Demo accounts created successfully!');
        $this->command->info('Admin: admin@demo.com / demo1234');
        $this->command->info('Customer: customer@demo.com / demo1234');
    }
}


