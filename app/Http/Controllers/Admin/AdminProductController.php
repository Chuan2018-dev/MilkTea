<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $products = $query->latest()->paginate(15);
        $categories = [
            'milk_tea' => 'Milk Tea',
            'fruit_tea' => 'Fruit Tea',
            'smoothie' => 'Smoothie',
            'coffee' => 'Coffee',
            'other' => 'Other',
        ];

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = [
            'milk_tea' => 'Milk Tea',
            'fruit_tea' => 'Fruit Tea',
            'smoothie' => 'Smoothie',
            'coffee' => 'Coffee',
            'other' => 'Other',
        ];

        $sizes = ['small', 'medium', 'large'];
        $sugarLevels = ['0%', '30%', '50%', '70%', '100%'];
        $iceLevels = ['no_ice', 'less', 'regular', 'extra'];

        return view('admin.products.create', compact('categories', 'sizes', 'sugarLevels', 'iceLevels'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'category' => 'required|in:milk_tea,fruit_tea,smoothie,coffee,other',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'available_sizes' => 'required|array|min:1',
            'available_sizes.*' => 'in:small,medium,large',
            'available_sugar_levels' => 'required|array|min:1',
            'available_sugar_levels.*' => 'in:0%,30%,50%,70%,100%',
            'available_ice_levels' => 'required|array|min:1',
            'available_ice_levels.*' => 'in:no_ice,less,regular,extra',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->boolean('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products', $filename);
            $data['image'] = $filename;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the product.
     */
    public function edit(Product $product)
    {
        $categories = [
            'milk_tea' => 'Milk Tea',
            'fruit_tea' => 'Fruit Tea',
            'smoothie' => 'Smoothie',
            'coffee' => 'Coffee',
            'other' => 'Other',
        ];

        $sizes = ['small', 'medium', 'large'];
        $sugarLevels = ['0%', '30%', '50%', '70%', '100%'];
        $iceLevels = ['no_ice', 'less', 'regular', 'extra'];

        return view('admin.products.edit', compact('product', 'categories', 'sizes', 'sugarLevels', 'iceLevels'));
    }

    /**
     * Update the product.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'category' => 'required|in:milk_tea,fruit_tea,smoothie,coffee,other',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'available_sizes' => 'required|array|min:1',
            'available_sizes.*' => 'in:small,medium,large',
            'available_sugar_levels' => 'required|array|min:1',
            'available_sugar_levels.*' => 'in:0%,30%,50%,70%,100%',
            'available_ice_levels' => 'required|array|min:1',
            'available_ice_levels.*' => 'in:no_ice,less,regular,extra',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->boolean('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::delete('public/products/' . $product->image);
            }

            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products', $filename);
            $data['image'] = $filename;
        }

        $product->update($data);

        $redirectQuery = $request->only(['search', 'category', 'status', 'page']);

        return redirect()->route('admin.products.index', $redirectQuery)
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the product.
     */
    public function destroy(Product $product)
    {
        // Delete image
        if ($product->image) {
            Storage::delete('public/products/' . $product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Toggle product status.
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'active' : 'inactive';

        return redirect()->back()
            ->with('success', "Product status updated to {$status}.");
    }
}
