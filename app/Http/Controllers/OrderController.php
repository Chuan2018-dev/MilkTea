<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Addon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
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
     * Display order history for customer.
     */
    public function index()
    {
        $orders = Auth::user()->orders()
            ->recent()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Display order details.
     */
    public function show(Order $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $order->load('items.product');

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Show checkout form.
     */
    public function checkout()
    {
        [$cart, $removedItems] = $this->sanitizeCart(session()->get('cart', []));

        if ($removedItems > 0) {
            session()->put('cart', $cart);
            session()->flash('warning', 'Some inactive products were removed from your cart.');
        }

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
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
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'size' => $item['size'],
                    'sugar_level' => $item['sugar_level'],
                    'ice_level' => $item['ice_level'],
                    'addons' => $addons,
                    'unit_price' => $item['unit_price'],
                    'addons_total' => $addonsTotal,
                    'subtotal' => $itemSubtotal,
                ];
            }
        }

        $tax = round($subtotal * 0.08, 2);
        $total = $subtotal + $tax;

        $user = Auth::user();

        return view('customer.orders.checkout', compact('cartItems', 'subtotal', 'tax', 'total', 'user'));
    }

    /**
     * Process the order.
     */
    public function store(Request $request)
    {
        [$cart, $removedItems] = $this->sanitizeCart(session()->get('cart', []));

        if ($removedItems > 0) {
            session()->put('cart', $cart);
            return redirect()->route('cart.index')
                ->with('warning', 'Some products became inactive and were removed from your cart. Please review your cart.');
        }

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_address' => 'required|string|max:500',
            'payment_method' => 'required|in:cash,card,ewallet',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            foreach ($cart as $item) {
                $addonsTotal = 0;
                if (!empty($item['addons'])) {
                    $addonsTotal = Addon::active()->whereIn('id', $item['addons'])->sum('price');
                }
                $subtotal += ($item['unit_price'] + $addonsTotal) * $item['quantity'];
            }

            $tax = round($subtotal * 0.08, 2);
            $total = $subtotal + $tax;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'notes' => $request->notes,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'delivery_address' => $request->delivery_address,
                'ordered_at' => now(),
            ]);

            // Create order items
            foreach ($cart as $item) {
                $product = Product::active()->find($item['product_id']);
                if ($product) {
                    $addons = [];
                    $addonsTotal = 0;

                    if (!empty($item['addons'])) {
                        $addonModels = Addon::active()->whereIn('id', $item['addons'])->get();
                        foreach ($addonModels as $addon) {
                            $addons[] = [
                                'id' => $addon->id,
                                'name' => $addon->name,
                                'price' => $addon->price,
                            ];
                            $addonsTotal += $addon->price;
                        }
                    }

                    $itemSubtotal = ($item['unit_price'] + $addonsTotal) * $item['quantity'];

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit_price' => $item['unit_price'],
                        'quantity' => $item['quantity'],
                        'size' => $item['size'],
                        'sugar_level' => $item['sugar_level'],
                        'ice_level' => $item['ice_level'],
                        'addons' => $addons,
                        'addons_total' => $addonsTotal,
                        'subtotal' => $itemSubtotal,
                        'special_instructions' => $item['special_instructions'] ?? '',
                    ]);
                }
            }

            DB::commit();

            // Clear cart
            session()->forget('cart');

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully! Your order number is ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    /**
     * Cancel order.
     */
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.index')->with('success', 'Order cancelled successfully.');
    }
}
