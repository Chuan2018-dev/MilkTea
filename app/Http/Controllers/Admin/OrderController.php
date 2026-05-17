<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display list of orders.
     */
    public function index(Request $request): View
    {
        $query = Order::with('user');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->byStatus($request->status);
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        $statuses = ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'];

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    /**
     * Display order details.
     */
    public function show(Order $order): View
    {
        $order->load(['user', 'items.product', 'items.size', 'items.addOns']);
        $statuses = ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'];

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,preparing,ready,completed,cancelled'],
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Update payment status.
     */
    public function updatePayment(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'in:pending,paid,failed,refunded'],
        ]);

        $order->update(['payment_status' => $validated['payment_status']]);

        return redirect()->back()->with('success', 'Payment status updated successfully!');
    }
}
