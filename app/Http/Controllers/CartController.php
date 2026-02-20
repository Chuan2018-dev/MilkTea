<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Addon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Remove unavailable products/add-ons from the cart.
     */
    private function sanitizeCart(array $cart): array
    {
        $cleanCart = [];
        $removedItems = 0;

        foreach ($cart as $key => $item) {
            $product = Product::active()->find($item['product_id'] ?? null);

            if (! $product) {
                $removedItems++;
                continue;
            }

            $addonIds = Addon::active()
                ->whereIn('id', $item['addons'] ?? [])
                ->pluck('id')
                ->values()
                ->all();

            $item['addons'] = $addonIds;
            $cleanCart[$key] = $item;
        }

        return [$cleanCart, $removedItems];
    }

    /**
     * Display the cart.
     */
    public function index()
    {
        [$cart, $removedItems] = $this->sanitizeCart(session()->get('cart', []));

        if ($removedItems > 0) {
            session()->put('cart', $cart);
            session()->flash('warning', 'Some inactive products were removed from your cart.');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            $product = Product::active()->find($item['product_id']);
            if ($product) {
                $addons = [];
                $addonsTotal = 0;

                if (!empty($item['addons'])) {
                    $addonModels = Addon::active()->whereIn('id', $item['addons'])->get();
                    foreach ($addonModels as $addon) {
                        $addons[] = $addon;
                        $addonsTotal += $addon->price;
                    }
                }

                $itemSubtotal = ($item['unit_price'] + $addonsTotal) * $item['quantity'];
                $subtotal += $itemSubtotal;

                $cartItems[] = [
                    'key' => $key,
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'size' => $item['size'],
                    'sugar_level' => $item['sugar_level'],
                    'ice_level' => $item['ice_level'],
                    'addons' => $addons,
                    'unit_price' => $item['unit_price'],
                    'addons_total' => $addonsTotal,
                    'subtotal' => $itemSubtotal,
                    'special_instructions' => $item['special_instructions'] ?? '',
                ];
            }
        }

        $tax = round($subtotal * 0.08, 2);
        $total = $subtotal + $tax;

        return view('customer.cart.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    /**
     * Add item to cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
            'size' => 'required|in:small,medium,large',
            'sugar_level' => 'required|in:0%,30%,50%,70%,100%',
            'ice_level' => 'required|in:no_ice,less,regular,extra',
            'addons' => 'nullable|array',
            'addons.*' => 'exists:addons,id',
            'special_instructions' => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (! $product->is_active) {
            return redirect()->route('products.index')
                ->with('error', 'This product is currently unavailable.');
        }

        [$cart, $removedItems] = $this->sanitizeCart(session()->get('cart', []));
        if ($removedItems > 0) {
            session()->flash('warning', 'Some inactive products were removed from your cart.');
        }

        $addonIds = Addon::active()
            ->whereIn('id', $request->addons ?? [])
            ->pluck('id')
            ->values()
            ->all();

        // Generate unique key for cart item
        $cartKey = uniqid('cart_');

        $cart[$cartKey] = [
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'size' => $request->size,
            'sugar_level' => $request->sugar_level,
            'ice_level' => $request->ice_level,
            'addons' => $addonIds,
            'unit_price' => $product->getPriceForSize($request->size),
            'special_instructions' => $request->special_instructions,
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Item added to cart!');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, $key)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $request->quantity;
            session()->put('cart', $cart);

            return redirect()->route('cart.index')->with('success', 'Cart updated!');
        }

        return redirect()->route('cart.index')->with('error', 'Item not found in cart.');
    }

    /**
     * Remove item from cart.
     */
    public function remove($key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);

            return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
        }

        return redirect()->route('cart.index')->with('error', 'Item not found in cart.');
    }

    /**
     * Clear the cart.
     */
    public function clear()
    {
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }

    /**
     * Get cart count for AJAX.
     */
    public function count()
    {
        $cart = session()->get('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));

        return response()->json(['count' => $count]);
    }
}
