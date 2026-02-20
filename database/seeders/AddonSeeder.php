<?php

namespace Database\Seeders;

use App\Models\Addon;
use Illuminate\Database\Seeder;

class AddonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addons = [
            [
                'name' => 'Tapioca Pearls (Boba)',
                'price' => 0.75,
            ],
            [
                'name' => 'Pudding',
                'price' => 0.75,
            ],
            [
                'name' => 'Grass Jelly',
                'price' => 0.75,
            ],
            [
                'name' => 'Aloe Vera',
                'price' => 0.75,
            ],
            [
                'name' => 'Red Bean',
                'price' => 0.75,
            ],
            [
                'name' => 'Coconut Jelly',
                'price' => 0.75,
            ],
            [
                'name' => 'Lychee Jelly',
                'price' => 0.75,
            ],
            [
                'name' => 'Extra Tapioca',
                'price' => 0.50,
            ],
            [
                'name' => 'Cheese Foam',
                'price' => 1.00,
            ],
            [
                'name' => 'Whipped Cream',
                'price' => 0.50,
            ],
        ];

        foreach ($addons as $addon) {
            Addon::create($addon);
        }
    }
}
