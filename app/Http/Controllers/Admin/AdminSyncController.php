<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class AdminSyncController extends Controller
{
    /**
     * Return a compact hash that changes whenever admin-facing data changes.
     */
    public function state(): JsonResponse
    {
        $state = [
            'products_count' => Product::count(),
            'products_latest' => Product::max('updated_at'),
            'addons_count' => Addon::count(),
            'addons_latest' => Addon::max('updated_at'),
            'orders_count' => Order::count(),
            'orders_latest' => Order::max('updated_at'),
        ];

        return response()->json([
            'hash' => sha1(json_encode($state)),
            'generated_at' => now()->toIso8601String(),
        ]);
    }
}
