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
        // Create or update default admin account (prevents duplicate email error)
        User::updateOrCreate(
            ['email' => 'admin@milkteashop.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '(555) 000-0000',
                'address' => 'Admin Office, 123 Tea Street, Milk City, MC 12345',
            ]
        );

        // Create or update sample customer accounts
        $customers = [
            [
                'name' => 'John Customer',
                'email' => 'john@example.com',
                'phone' => '(555) 111-1111',
                'address' => '456 Customer Ave, Milk City, MC 12345',
            ],
            [
                'name' => 'Jane Customer',
                'email' => 'jane@example.com',
                'phone' => '(555) 222-2222',
                'address' => '789 Customer Blvd, Milk City, MC 12345',
            ],
        ];

        foreach ($customers as $customer) {
            User::updateOrCreate(
                ['email' => $customer['email']],
                [
                    'name' => $customer['name'],
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'phone' => $customer['phone'],
                    'address' => $customer['address'],
                ]
            );
        }
    }
}
