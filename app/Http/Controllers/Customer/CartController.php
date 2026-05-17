<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the cart.
     */
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            $product = Product::find($item['product_id']);
            $size = Size::find($item['size_id']);
            $addOns = AddOn::whereIn('id', $item['add_ons'] ?? [])->get();

            if ($product && $size) {
                $itemTotal = $this->calculateItemTotal($product, $size, $addOns, $item['quantity']);
                $subtotal += $itemTotal;

                $cartItems[] = [
                    'key' => $key,
                    'product' => $product,
                    'size' => $size,
                    'addOns' => $addOns,
                    'quantity' => $item['quantity'],
                    'sugar_level' => $item['sugar_level'] ?? '50%',
                    'ice_level' => $item['ice_level'] ?? 'normal_ice',
                    'special_instructions' => $item['special_instructions'] ?? '',
                    'unit_price' => $this->calculateUnitPrice($product, $size, $addOns),
                    'total' => $itemTotal,
                ];
            }
        }

        $tax = $subtotal * 0.08; // 8% tax
        $total = $subtotal + $tax;

        return view('customer.cart.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    /**
     * Add item to cart.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'size_id' => ['required', 'exists:sizes,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'sugar_level' => ['required', 'in:0%,25%,50%,75%,100%'],
            'ice_level' => ['required', 'in:no_ice,less_ice,normal_ice'],
            'add_ons' => ['nullable', 'array'],
            'add_ons.*' => ['exists:add_ons,id'],
            'special_instructions' => ['nullable', 'string', 'max:255'],
        ]);

        $cart = session()->get('cart', []);

        // Generate unique key for cart item
        $cartKey = $this->generateCartKey($validated);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $validated['quantity'];
        } else {
            $cart[$cartKey] = [
                'product_id' => $validated['product_id'],
                'size_id' => $validated['size_id'],
                'quantity' => $validated['quantity'],
                'sugar_level' => $validated['sugar_level'],
                'ice_level' => $validated['ice_level'],
                'add_ons' => $validated['add_ons'] ?? [],
                'special_instructions' => $validated['special_instructions'] ?? '',
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Item added to cart!');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, string $key): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $validated['quantity'];
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    /**
     * Remove item from cart.
     */
    public function destroy(string $key): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }

    /**
     * Clear the cart.
     */
    public function clear(): RedirectResponse
    {
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }

    /**
     * Generate unique cart key based on item attributes.
     */
    private function generateCartKey(array $item): string
    {
        $addOns = $item['add_ons'] ?? [];
        sort($addOns);
        
        return md5(implode('-', [
            $item['product_id'],
            $item['size_id'],
            $item['sugar_level'] ?? '50%',
            $item['ice_level'] ?? 'normal_ice',
            implode(',', $addOns),
            $item['special_instructions'] ?? '',
        ]));
    }

    /**
     * Calculate unit price for an item.
     */
    private function calculateUnitPrice(Product $product, Size $size, $addOns): float
    {
        $price = $product->base_price + $size->price_adjustment;

        foreach ($addOns as $addOn) {
            $price += $addOn->price;
        }

        return $price;
    }

    /**
     * Calculate total for an item.
     */
    private function calculateItemTotal(Product $product, Size $size, $addOns, int $quantity): float
    {
        return $this->calculateUnitPrice($product, $size, $addOns) * $quantity;
    }
}
