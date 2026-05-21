@extends('layouts.app')

@section('title', 'My Orders - Milk Tea Shop')
@section('auto_sync', 'true')
@section('auto_sync_interval', '20000')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-clock-history me-2"></i>My Orders</h2>
            <p class="text-muted">View and track your order history</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>
                                                <span class="fw-bold">{{ $order->order_number }}</span>
                                            </td>
                                            <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                            <td>{{ $order->items->sum('quantity') }} items</td>
                                            <td class="fw-bold">{{ $order->formatted_total }}</td>
                                            <td>
                                                <span class="badge badge-{{ $order->status }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $order->payment_status }}">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('customer.orders.show', $order) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye me-1"></i>View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <h4 class="mt-4">No orders yet</h4>
                            <p class="text-muted">You haven't placed any orders yet.</p>
                            <a href="{{ route('menu.index') }}" class="btn btn-primary">
                                <i class="bi bi-menu-button-wide me-2"></i>Browse Menu
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
