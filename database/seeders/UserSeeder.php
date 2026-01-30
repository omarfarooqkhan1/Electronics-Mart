<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Electronics Mart Admin',
            'email' => 'admin@electronicsmart.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'registration_date' => now(),
        ]);

        // Create test customer
        User::create([
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'email_verified_at' => now(),
            'registration_date' => now(),
        ]);

        $this->command->info('Users seeded successfully!');
    }
}