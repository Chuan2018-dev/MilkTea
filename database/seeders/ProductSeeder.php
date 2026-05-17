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
            [
                'name' => 'Classic Milk Tea',
                'description' => 'Our signature black milk tea with a rich, creamy flavor.',
                'base_price' => 80,
                'category' => 'milk_tea',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Wintermelon Milk Tea',
                'description' => 'Refreshing wintermelon flavor blended with creamy milk.',
                'base_price' => 85,
                'category' => 'milk_tea',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Okinawa Milk Tea',
                'description' => 'Brown sugar flavored milk tea with a caramel taste.',
                'base_price' => 90,
                'category' => 'milk_tea',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Thai Milk Tea',
                'description' => 'Authentic Thai tea with a unique orange color and flavor.',
                'base_price' => 95,
                'category' => 'milk_tea',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Taro Milk Tea',
                'description' => 'Creamy taro flavor with a beautiful purple color.',
                'base_price' => 90,
                'category' => 'milk_tea',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Matcha Milk Tea',
                'description' => 'Premium Japanese green tea with milk.',
                'base_price' => 100,
                'category' => 'milk_tea',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Strawberry Fruit Tea',
                'description' => 'Fresh strawberry flavor with green tea base.',
                'base_price' => 85,
                'category' => 'fruit_tea',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Mango Fruit Tea',
                'description' => 'Tropical mango flavor with green tea base.',
                'base_price' => 85,
                'category' => 'fruit_tea',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Passion Fruit Tea',
                'description' => 'Refreshing passion fruit with green tea.',
                'base_price' => 80,
                'category' => 'fruit_tea',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Iced Americano',
                'description' => 'Strong espresso with cold water and ice.',
                'base_price' => 75,
                'category' => 'coffee',
                'sort_order' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Caramel Macchiato',
                'description' => 'Espresso with vanilla syrup, steamed milk, and caramel drizzle.',
                'base_price' => 110,
                'category' => 'coffee',
                'sort_order' => 11,
                'is_active' => true,
            ],
            [
                'name' => 'Hazelnut Latte',
                'description' => 'Smooth latte with hazelnut flavor.',
                'base_price' => 105,
                'category' => 'coffee',
                'sort_order' => 12,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
