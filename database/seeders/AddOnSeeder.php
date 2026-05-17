<?php

namespace Database\Seeders;

use App\Models\AddOn;
use Illuminate\Database\Seeder;

class AddOnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addOns = [
            [
                'name' => 'Pearl',
                'description' => 'Chewy tapioca pearls',
                'price' => 10,
                'category' => 'topping',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Nata',
                'description' => 'Sweet coconut jelly',
                'price' => 10,
                'category' => 'topping',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Pudding',
                'description' => 'Creamy egg pudding',
                'price' => 15,
                'category' => 'topping',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Grass Jelly',
                'description' => 'Refreshing herbal jelly',
                'price' => 10,
                'category' => 'topping',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Aloe Vera',
                'description' => 'Healthy aloe vera pieces',
                'price' => 15,
                'category' => 'topping',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Red Bean',
                'description' => 'Sweet red beans',
                'price' => 15,
                'category' => 'topping',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($addOns as $addOn) {
            AddOn::create($addOn);
        }
    }
}
