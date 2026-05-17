<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the customer dashboard.
     */
    public function index(): View
    {
        $recentOrders = Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $totalOrders = Order::where('user_id', auth()->id())->count();
        $pendingOrders = Order::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'confirmed', 'preparing'])
            ->count();

        return view('customer.dashboard', compact('recentOrders', 'totalOrders', 'pendingOrders'));
    }
}
