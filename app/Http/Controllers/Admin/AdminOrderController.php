<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::with('user');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->status($request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by order number
        if ($request->has('search') && $request->search) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->recent()->paginate(20);

        $statuses = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'preparing' => 'Preparing',
            'ready' => 'Ready',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load('items.product', 'user');

        $statuses = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'preparing' => 'Preparing',
            'ready' => 'Ready',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];

        $paymentStatuses = [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
        ];

        return view('admin.orders.show', compact('order', 'statuses', 'paymentStatuses'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $updateData = ['status' => $newStatus];

        // If order is completed, set completed_at
        if ($newStatus === 'completed' && $oldStatus !== 'completed') {
            $updateData['completed_at'] = now();
        }

        $order->update($updateData);

        return redirect()->back()
            ->with('success', "Order status updated from {$oldStatus} to {$newStatus}!");
    }

    /**
     * Update payment status.
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return redirect()->back()
            ->with('success', 'Payment status updated successfully!');
    }

    /**
     * Print order receipt.
     */
    public function print(Order $order)
    {
        $order->load('items');

        return view('admin.orders.print', compact('order'));
    }
}
