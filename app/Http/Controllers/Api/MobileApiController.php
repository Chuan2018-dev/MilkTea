<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MobileApiController extends Controller
{
    public function catalog(): JsonResponse
    {
        return response()->json([
            'products' => Product::active()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->map(fn (Product $product) => $this->productPayload($product))
                ->values(),
            'sizes' => Size::active()
                ->orderBy('sort_order')
                ->get()
                ->map(fn (Size $size) => $this->sizePayload($size))
                ->values(),
            'add_ons' => AddOn::active()
                ->orderBy('category')
                ->orderBy('sort_order')
                ->get()
                ->map(fn (AddOn $addOn) => $this->addOnPayload($addOn))
                ->values(),
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', Str::lower($validated['email']))->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid email or password.'], 422);
        }

        return response()->json([
            'token' => $this->issueToken($user),
            'user' => $this->userPayload($user),
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            'email' => Str::lower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'email_verified_at' => now(),
        ]);

        return response()->json([
            'token' => $this->issueToken($user),
            'user' => $this->userPayload($user),
        ], 201);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $this->userPayload($request->user()),
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user = $request->user();
        $user->name = trim($validated['name']);
        $user->phone = $validated['phone'] ?? null;
        $user->address = $validated['address'] ?? null;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'user' => $this->userPayload($user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->forceFill([
            'api_token_hash' => null,
            'api_token_created_at' => null,
        ])->save();

        return response()->json(['message' => 'Logged out.']);
    }

    public function orders(Request $request): JsonResponse
    {
        $query = Order::with(['user', 'items.product', 'items.size', 'items.addOns'])
            ->orderByDesc('created_at');

        if (! $request->user()->isAdmin()) {
            $query->where('user_id', $request->user()->id);
        }

        return response()->json([
            'orders' => $query->get()
                ->map(fn (Order $order) => $this->orderPayload($order))
                ->values(),
        ]);
    }

    public function storeOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'payment_method' => ['required', Rule::in(['cash', 'card'])],
            'pickup_method' => ['required', Rule::in(['in_store', 'drive_thru'])],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', Rule::exists('products', 'id')],
            'items.*.size_id' => ['required', 'integer', Rule::exists('sizes', 'id')],
            'items.*.add_on_ids' => ['sometimes', 'array'],
            'items.*.add_on_ids.*' => ['integer', Rule::exists('add_ons', 'id')],
            'items.*.sugar_level' => ['required', 'string', 'max:10'],
            'items.*.ice_level' => ['required', 'string', 'max:20'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'items.*.special_instructions' => ['nullable', 'string', 'max:500'],
        ]);

        $order = DB::transaction(function () use ($request, $validated) {
            $subtotal = 0;
            $preparedItems = [];

            foreach ($validated['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                $size = Size::findOrFail($itemData['size_id']);
                $addOns = AddOn::whereIn('id', $itemData['add_on_ids'] ?? [])->get();
                $unitPrice = (float) $product->base_price + (float) $size->price_adjustment;

                foreach ($addOns as $addOn) {
                    $unitPrice += (float) $addOn->price;
                }

                $lineTotal = $unitPrice * (int) $itemData['quantity'];
                $subtotal += $lineTotal;

                $preparedItems[] = [
                    'data' => $itemData,
                    'add_ons' => $addOns,
                    'unit_price' => $unitPrice,
                    'total_price' => $lineTotal,
                ];
            }

            $tax = round($subtotal * 0.08, 2);
            $total = round($subtotal + $tax, 2);

            $order = Order::create([
                'user_id' => $request->user()->id,
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
            ]);

            foreach ($preparedItems as $preparedItem) {
                $itemData = $preparedItem['data'];
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $itemData['product_id'],
                    'size_id' => $itemData['size_id'],
                    'sugar_level' => $itemData['sugar_level'],
                    'ice_level' => $itemData['ice_level'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $preparedItem['unit_price'],
                    'total_price' => $preparedItem['total_price'],
                    'special_instructions' => $itemData['special_instructions'] ?? null,
                ]);

                foreach ($preparedItem['add_ons'] as $addOn) {
                    $orderItem->addOns()->attach($addOn->id, ['price' => $addOn->price]);
                }
            }

            return $order->fresh(['user', 'items.product', 'items.size', 'items.addOns']);
        });

        return response()->json([
            'order' => $this->orderPayload($order),
        ], 201);
    }

    public function updateOrderStatus(Request $request, Order $order): JsonResponse
    {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'])],
        ]);

        $order->update(['status' => $validated['status']]);

        return response()->json([
            'order' => $this->orderPayload($order->fresh(['user', 'items.product', 'items.size', 'items.addOns'])),
        ]);
    }

    public function updatePaymentStatus(Request $request, Order $order): JsonResponse
    {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'payment_status' => ['required', Rule::in(['pending', 'paid', 'failed', 'refunded'])],
        ]);

        $order->update(['payment_status' => $validated['payment_status']]);

        return response()->json([
            'order' => $this->orderPayload($order->fresh(['user', 'items.product', 'items.size', 'items.addOns'])),
        ]);
    }

    public function updateProduct(Request $request, Product $product): JsonResponse
    {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'base_price' => $validated['base_price'],
            'category' => $validated['category'],
            'is_active' => $validated['is_active'] ?? $product->is_active,
        ]);

        return response()->json([
            'product' => $this->productPayload($product->refresh()),
        ]);
    }

    public function updateAddOn(Request $request, AddOn $addOn): JsonResponse
    {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['sometimes', 'string', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $addOn->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'category' => $validated['category'] ?? $addOn->category,
            'is_active' => $validated['is_active'] ?? $addOn->is_active,
        ]);

        return response()->json([
            'add_on' => $this->addOnPayload($addOn->refresh()),
        ]);
    }

    public function updateSize(Request $request, Size $size): JsonResponse
    {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:100'],
            'display_name' => ['required', 'string', 'max:100'],
            'price_adjustment' => ['required', 'numeric'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $size->update([
            'name' => $validated['name'] ?? $size->name,
            'display_name' => $validated['display_name'],
            'price_adjustment' => $validated['price_adjustment'],
            'is_active' => $validated['is_active'] ?? $size->is_active,
        ]);

        return response()->json([
            'size' => $this->sizePayload($size->refresh()),
        ]);
    }

    private function ensureAdmin(Request $request): void
    {
        abort_unless($request->user()?->isAdmin(), 403, 'Admin access required.');
    }

    private function issueToken(User $user): string
    {
        $token = Str::random(80);

        $user->forceFill([
            'api_token_hash' => hash('sha256', $token),
            'api_token_created_at' => now(),
        ])->save();

        return $token;
    }

    private function userPayload(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'phone' => $user->phone ?? '',
            'address' => $user->address ?? '',
        ];
    }

    private function productPayload(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description ?? '',
            'base_price' => (float) $product->base_price,
            'category' => $product->category,
            'image_url' => $product->image_url,
            'is_active' => (bool) $product->is_active,
        ];
    }

    private function sizePayload(Size $size): array
    {
        return [
            'id' => $size->id,
            'name' => $size->name,
            'display_name' => $size->display_name,
            'price_adjustment' => (float) $size->price_adjustment,
            'is_active' => (bool) $size->is_active,
        ];
    }

    private function addOnPayload(AddOn $addOn): array
    {
        return [
            'id' => $addOn->id,
            'name' => $addOn->name,
            'description' => $addOn->description ?? '',
            'price' => (float) $addOn->price,
            'category' => $addOn->category,
            'image_url' => $addOn->image_url,
            'is_active' => (bool) $addOn->is_active,
        ];
    }

    private function orderPayload(Order $order): array
    {
        return [
            'id' => $order->id,
            'number' => $order->order_number,
            'user_email' => $order->user?->email ?? '',
            'customer_name' => $order->customer_name ?? $order->user?->name ?? '',
            'contact_number' => $order->contact_number ?? '',
            'address' => $order->delivery_address ?? '',
            'payment_method' => $order->payment_method,
            'pickup_method' => $order->pickup_method,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'notes' => $order->notes ?? '',
            'subtotal' => (float) $order->subtotal,
            'tax' => (float) $order->tax,
            'total' => (float) $order->total,
            'created_at' => optional($order->created_at)->toIso8601String(),
            'items' => $order->items
                ->map(fn (OrderItem $item) => $this->orderItemPayload($item))
                ->values(),
        ];
    }

    private function orderItemPayload(OrderItem $item): array
    {
        return [
            'product' => $this->productPayload($item->product),
            'size' => $this->sizePayload($item->size),
            'sugar_level' => $item->sugar_level,
            'ice_level' => $item->ice_level,
            'quantity' => $item->quantity,
            'unit_price' => (float) $item->unit_price,
            'total_price' => (float) $item->total_price,
            'special_instructions' => $item->special_instructions ?? '',
            'add_ons' => $item->addOns
                ->map(fn (AddOn $addOn) => $this->addOnPayload($addOn))
                ->values(),
        ];
    }
}
