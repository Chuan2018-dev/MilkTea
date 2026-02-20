<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Milk Tea
            [
                'name' => 'Classic Milk Tea',
                'description' => 'Our signature black milk tea with chewy tapioca pearls. A timeless favorite!',
                'base_price' => 4.50,
                'category' => 'milk_tea',
                'available_sizes' => ['small', 'medium', 'large'],
                'available_sugar_levels' => ['0%', '30%', '50%', '70%', '100%'],
                'available_ice_levels' => ['no_ice', 'less', 'regular', 'extra'],
            ],
            [
                'name' => 'Brown Sugar Milk Tea',
                'description' => 'Rich brown sugar syrup combined with fresh milk and tapioca pearls.',
                'base_price' => 5.50,
                'category' => 'milk_tea',
                'available_sizes' => ['small', 'medium', 'large'],
                'available_sugar_levels' => ['30%', '50%', '70%', '100%'],
                'available_ice_levels' => ['no_ice', 'less', 'regular', 'extra'],
            ],
            [
                'name' => 'Taro Milk Tea',
                'description' => 'Creamy taro flavor blended with milk tea. A purple delight!',
                'base_price' => 5.00,
                'category' => 'milk_tea',
                'available_sizes' => ['small', 'medium', 'large'],
                'available_sugar_levels' => ['0%', '30%', '50%', '70%', '100%'],
                'available_ice_levels' => ['no_ice', 'less', 'regular', 'extra'],
            ],
            [
                'name' => 'Matcha Milk Tea',
                'description' => 'Premium Japanese matcha green tea with milk.',
                'base_price' => 5.50,
                'category' => 'milk_tea',
                'available_sizes' => ['small', 'medium', 'large'],
                'available_sugar_levels' => ['0%', '30%', '50%', '70%', '100%'],
                'available_ice_levels' => ['no_ice', 'less', 'regular', 'extra'],
            ],
            [
                'name' => 'Thai Milk Tea',
                'description' => 'Authentic Thai tea with condensed milk. Sweet and creamy!',
                'base_price' => 5.00,
                'category' => 'milk_tea',
                'available_sizes' => ['small', 'medium', 'large'],
                'available_sugar_levels' => ['30%', '50%', '70%', '100%'],
                'available_ice_levels' => ['no_ice', 'less', 'regular', 'extra'],
            ],
            [
                'name' => 'Honey Milk Tea',
                'description' => 'Black tea with natural honey and milk.',
                'base_price' => 4.75,
                'category' => 'milk_tea',
                'available_sizes' => ['small', 'medium', 'large'],
                'available_sugar_levels' => ['0%', '30%', '50%', '70%', '100%'],
                'available_ice_levels' => ['no_ice', 'less', 'regular', 'extra'],
            ],

            // Fruit Tea
            [
                'name' => 'Passion Fruit Green Tea',
                'description' => 'Refreshing green tea with passion fruit flavor.',
                'base_price' => 4.75,
                'category' => 'fruit_tea',
                'available_sizes' => ['small', 'medium', 'large'],
                'available_sugar_levels' => ['0%', '30%', '50%', '70%', '100%'],
                'available_ice_levels' => ['no_ice', 'less', 'regular', 'extra'],
            ],
            [
                'name' => 'Mango Green Tea',
                'description' => 'Sweet mango flavor with green tea base.',
                'base_price' => 5.00,
                'category' => 'fruit_tea',
                'available_sizes' => ['small', 'medium', 'large'],
                'available_sugar_levels' => ['0%', '30%', '50%', '70%', '100%'],
                'available_ice_levels' => ['no_ice', 'less', 'regular', 'extra'],
            ],
            [
                'name' => 'Peach Oolong Tea',
                'description' => 'Fragrant oolong tea with peach flavor.',
                'base_price' => 4.75,
                'category' => 'fruit_tea',
                'available_sizes' => ['small', 'medium', 'large'],
                'available_sugar_levels' => ['0%', '30%', '50%', '70%', '100%'],
                'available_ice_levels' => ['no_ice', 'less', 'regular', 'extra'],
            ],
            [
                'name' => 'Lychee Black Tea',
                'description' => 'Exotic lychee flavor with black tea.',
                'base_price' => 5.00,
                'category' => 'fruit_tea',
                'available_sizes' => ['small', 'medium', 'large'],
                'available_sugar_levels' => ['0%', '30%', '50%', '70%', '100%'],
                'available_ice_levels' => ['no_ice', 'less', 'regular', 'extra'],
            ],

            // Smoothies
            [
                'name' => 'Mango Smoothie',
                'description' => 'Fresh mango blended with ice. A tropical treat!',
                'base_price' => 5.50,
                'category' => 'smoothie',
                'available_sizes' => ['small', 'medium', 'large'],
                'available_sugar_levels' => ['0%', '30%', '50%', '70%', '100%'],
                'available_ice_levels' => ['no_ice', 'less', 'regular'],
            ],
            [
                'name' => 'Strawberry Smoothie',
                'description' => 'Sweet strawberries blended to perfection.',
                'base_price' => 5.50,
                'category' => 'smoothie',
                'available_sizes' => ['small', 'medium', 'large'],
                'available_sugar_levels' => ['0%', '30%', '50%', '70%', '100%'],
                'available_ice_levels' => ['no_ice', 'less', 'regular'],
            ],
            [
                'name' => 'Taro Smoothie',
                'description' => 'Creamy taro smoothie with a hint of vanilla.',
                'base_price' => 5.50,
                'category' => 'smoothie',
                'available_sizes' => ['small', 'medium', 'large'],
                'available_sugar_levels' => ['0%', '30%', '50%', '70%', '100%'],
                'available_ice_levels' => ['no_ice', 'less', 'regular'],
            ],

            // Coffee
            [
                'name' => 'Vietnamese Iced Coffee',
                'description' => 'Strong coffee with sweet condensed milk over ice.',
                'base_price' => 4.50,
                'category' => 'coffee',
                'available_sizes' => ['small', 'medium', 'large'],
                'available_sugar_levels' => ['0%', '30%', '50%', '70%', '100%'],
                'available_ice_levels' => ['no_ice', 'less', 'regular', 'extra'],
            ],
            [
                'name' => 'Caramel Macchiato',
                'description' => 'Espresso with vanilla syrup, steamed milk, and caramel drizzle.',
                'base_price' => 5.50,
                'category' => 'coffee',
                'available_sizes' => ['small', 'medium', 'large'],
                'available_sugar_levels' => ['0%', '30%', '50%', '70%', '100%'],
                'available_ice_levels' => ['no_ice', 'less', 'regular', 'extra'],
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
