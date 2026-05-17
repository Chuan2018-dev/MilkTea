<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Show checkout form.
     */
    public function index(): View|RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

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
                    'product' => $product,
                    'size' => $size,
                    'addOns' => $addOns,
                    'quantity' => $item['quantity'],
                    'sugar_level' => $item['sugar_level'] ?? '50%',
                    'ice_level' => $item['ice_level'] ?? 'normal_ice',
                    'total' => $itemTotal,
                ];
            }
        }

        $tax = $subtotal * 0.08; // 8% tax
        $total = $subtotal + $tax;

        return view('customer.checkout.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    /**
     * Process checkout.
     */
    public function store(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'payment_method' => ['required', 'in:cash,card'],
            'pickup_method' => ['required', 'in:in_store,drive_thru'],
            'pickup_time' => ['nullable', 'date', 'after:now'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                $size = Size::find($item['size_id']);
                $addOns = AddOn::whereIn('id', $item['add_ons'] ?? [])->get();
                
                if ($product && $size) {
                    $subtotal += $this->calculateItemTotal($product, $size, $addOns, $item['quantity']);
                }
            }

            $tax = $subtotal * 0.08;
            $total = $subtotal + $tax;

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $validated['customer_name'],
                'contact_number' => $validated['contact_number'],
                'delivery_address' => $validated['address'],
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'notes' => $validated['notes'] ?? null,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'pickup_method' => $validated['pickup_method'],
                'pickup_time' => $validated['pickup_time'] ?? null,
            ]);

            // Create order items
            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                $size = Size::find($item['size_id']);
                $addOns = AddOn::whereIn('id', $item['add_ons'] ?? [])->get();

                if ($product && $size) {
                    $unitPrice = $this->calculateUnitPrice($product, $size, $addOns);
                    $totalPrice = $unitPrice * $item['quantity'];

                    $orderItem = OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'size_id' => $item['size_id'],
                        'sugar_level' => $item['sugar_level'] ?? '50%',
                        'ice_level' => $item['ice_level'] ?? 'normal_ice',
                        'quantity' => $item['quantity'],
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                        'special_instructions' => $item['special_instructions'] ?? '',
                    ]);

                    // Attach add-ons
                    foreach ($addOns as $addOn) {
                        $orderItem->addOns()->attach($addOn->id, ['price' => $addOn->price]);
                    }
                }
            }

            DB::commit();

            // Clear cart
            session()->forget('cart');

            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Order placed successfully! Your order number is ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
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
