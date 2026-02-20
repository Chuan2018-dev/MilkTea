<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application home page.
     */
    public function index()
    {
        $featuredProducts = Product::active()
            ->inRandomOrder()
            ->take(6)
            ->get();

        $categories = [
            'milk_tea' => 'Milk Tea',
            'fruit_tea' => 'Fruit Tea',
            'smoothie' => 'Smoothies',
            'coffee' => 'Coffee',
        ];

        return view('customer.home', compact('featuredProducts', 'categories'));
    }

    /**
     * Show about page.
     */
    public function about()
    {
        return view('customer.about');
    }

    /**
     * Show contact page.
     */
    public function contact()
    {
        return view('customer.contact');
    }
}
