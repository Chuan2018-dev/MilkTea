<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Display the menu.
     */
    public function index(Request $request): View
    {
        $category = $request->get('category');
        
        $products = Product::active()
            ->when($category, function ($query, $category) {
                return $query->byCategory($category);
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $categories = Product::distinct()->pluck('category');

        return view('customer.menu.index', compact('products', 'categories', 'category'));
    }

    /**
     * Show product details for customization.
     */
    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $sizes = Size::active()->orderBy('sort_order')->get();
        $addOns = AddOn::active()->orderBy('category')->orderBy('sort_order')->get();

        return view('customer.menu.show', compact('product', 'sizes', 'addOns'));
    }
}
