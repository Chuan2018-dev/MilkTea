@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' - Milk Tea Shop')
@section('auto_sync', 'true')
@section('auto_sync_interval', '20000')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="bi bi-arrow-left me-2"></i>Back to Orders
            </a>
            <h2><i class="bi bi-receipt me-2"></i>Order Details</h2>
        </div>
    </div>

    <div class="row">
        <!-- Order Info -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ $order->order_number }}</h5>
                        <small class="text-muted">Placed on {{ $order->created_at->format('F d, Y at h:i A') }}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge badge-{{ $order->status }} fs-6">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Order Items -->
                    <h6 class="fw-bold mb-3">Order Items</h6>
                    @foreach($order->items as $item)
                        <div class="d-flex mb-3 pb-3 border-bottom">
                            <img src="{{ $item->product->image_url }}" 
                                 alt="{{ $item->product->name }}"
                                 class="rounded me-3"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                <p class="text-muted mb-1">
                                    Size: {{ $item->size->display_name }}
                                    <br>Sugar: {{ $item->sugar_level }}
                                    <br>Ice: {{ ucfirst(str_replace('_', ' ', $item->ice_level)) }}
                                    @if($item->addOns->count() > 0)
                                        <br>Add-ons: {{ $item->addOns->pluck('name')->join(', ') }}
                                    @endif
                                    @if($item->special_instructions)
                                        <br><small><i class="bi bi-chat-left-text"></i> {{ $item->special_instructions }}</small>
                                    @endif
                                </p>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">{{ $item->quantity }} x {{ $item->formatted_unit_price }}</span>
                                    <span class="fw-bold">{{ $item->formatted_total_price }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Order Totals -->
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>{{ $order->formatted_subtotal }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax</span>
                                <span>{{ $order->formatted_tax }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold fs-5">Total</span>
                                <span class="fw-bold fs-5 text-primary">{{ $order->formatted_total }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Notes -->
            @if($order->notes)
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="bi bi-chat-left-text me-2"></i>Order Notes</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Details Sidebar -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Order Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Customer</small>
                        <span class="fw-bold">{{ $order->customer_name ?? $order->user->name }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Contact Number</small>
                        <span class="fw-bold">{{ $order->contact_number ?? $order->user->phone }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Address</small>
                        <span class="fw-bold">{{ $order->delivery_address ?? $order->user->address }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Payment Method</small>
                        <span class="fw-bold">{{ ucfirst($order->payment_method) }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Payment Status</small>
                        <span class="badge badge-{{ $order->payment_status }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Pickup Method</small>
                        <span class="fw-bold">{{ ucfirst(str_replace('_', ' ', $order->pickup_method)) }}</span>
                    </div>
                    @if($order->pickup_time)
                        <div class="mb-0">
                            <small class="text-muted d-block">Pickup Time</small>
                            <span class="fw-bold">{{ $order->pickup_time->format('M d, Y h:i A') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if(in_array($order->status, ['pending', 'confirmed']))
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('customer.orders.cancel', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100"
                                    onclick="return confirm('Are you sure you want to cancel this order?')">
                                <i class="bi bi-x-circle me-2"></i>Cancel Order
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
