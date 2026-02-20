<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Addon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::active();

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->category($request->category);
        }

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);
        $categories = [
            'milk_tea' => 'Milk Tea',
            'fruit_tea' => 'Fruit Tea',
            'smoothie' => 'Smoothies',
            'coffee' => 'Coffee',
            'other' => 'Other',
        ];

        return view('customer.products.index', compact('products', 'categories'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $addons = Addon::active()->get();
        $relatedProducts = Product::active()
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('customer.products.show', compact('product', 'addons', 'relatedProducts'));
    }

    /**
     * Get product price for AJAX requests.
     */
    public function getPrice(Request $request, Product $product)
    {
        if (! $product->is_active) {
            return response()->json([
                'message' => 'This product is currently unavailable.',
            ], 422);
        }

        $size = $request->input('size', 'medium');
        $addonIds = $request->input('addons', []);

        $basePrice = $product->getPriceForSize($size);
        $addonsTotal = 0;

        if (!empty($addonIds)) {
            $addonsTotal = Addon::active()->whereIn('id', $addonIds)->sum('price');
        }

        $totalPrice = $basePrice + $addonsTotal;

        return response()->json([
            'base_price' => $basePrice,
            'addons_total' => $addonsTotal,
            'total_price' => $totalPrice,
            'formatted_price' => '$' . number_format($totalPrice, 2),
        ]);
    }
}
