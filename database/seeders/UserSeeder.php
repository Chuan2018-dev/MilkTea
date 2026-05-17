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
            'name' => 'Administrator',
            'email' => 'admin@milktea.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '09123456789',
            'address' => '123 Admin Street, Manila, Philippines',
        ]);

        // Create sample customer
        User::create([
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '09987654321',
            'address' => '456 Customer Ave, Quezon City, Philippines',
        ]);

        // Create additional random customers
        User::factory(5)->create();
    }
}
