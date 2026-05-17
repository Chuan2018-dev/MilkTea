<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            [
                'name' => 'small',
                'display_name' => 'Small (12oz)',
                'price_adjustment' => 0,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'medium',
                'display_name' => 'Medium (16oz)',
                'price_adjustment' => 10,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'large',
                'display_name' => 'Large (22oz)',
                'price_adjustment' => 20,
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($sizes as $size) {
            Size::create($size);
        }
    }
}
