<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show admin dashboard.
     */
    public function dashboard()
    {
        // Statistics
        $totalOrders = Order::count();
        $todayOrders = Order::today()->count();
        $pendingOrders = Order::status('pending')->count();
        $totalProducts = Product::count();
        $activeProducts = Product::active()->count();
        $totalCustomers = User::where('role', 'customer')->count();

        // Revenue statistics
        $todayRevenue = Order::today()->where('payment_status', 'paid')->sum('total');
        $monthRevenue = Order::whereMonth('created_at', now()->month)
            ->where('payment_status', 'paid')
            ->sum('total');

        // Recent orders
        $recentOrders = Order::recent()->take(10)->get();

        // Orders by status for chart
        $ordersByStatus = [
            'pending' => Order::status('pending')->count(),
            'confirmed' => Order::status('confirmed')->count(),
            'preparing' => Order::status('preparing')->count(),
            'ready' => Order::status('ready')->count(),
            'completed' => Order::status('completed')->count(),
            'cancelled' => Order::status('cancelled')->count(),
        ];

        // Weekly sales data
        $weeklySales = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sales = Order::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('total');
            $weeklySales[] = [
                'day' => $date->format('D'),
                'sales' => $sales,
            ];
        }

        return view('admin.dashboard', compact(
            'totalOrders',
            'todayOrders',
            'pendingOrders',
            'totalProducts',
            'activeProducts',
            'totalCustomers',
            'todayRevenue',
            'monthRevenue',
            'recentOrders',
            'ordersByStatus',
            'weeklySales'
        ));
    }
}
