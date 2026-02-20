@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="bi bi-bag me-2"></i>Order Details
    </h2>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.print', $order) }}" target="_blank" class="btn btn-secondary">
            <i class="bi bi-printer me-2"></i>Print
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <!-- Order Info -->
    <div class="col-lg-8">
        <!-- Status Cards -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <span class="text-muted small d-block">Order Status</span>
                        <span class="badge {{ $order->status_badge_class }} fs-6">
                            {{ $order->status_label }}
                        </span>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <span class="text-muted small d-block">Payment Status</span>
                        <span class="badge {{ $order->payment_status_badge_class }} fs-6">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted small d-block">Order Date</span>
                        <span class="fw-bold">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Status -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-arrow-repeat me-2"></i>Update Status
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="input-group">
                                <select class="form-select" name="status" required>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.orders.update-payment-status', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="input-group">
                                <select class="form-select" name="payment_status" required>
                                    @foreach($paymentStatuses as $key => $label)
                                        <option value="{{ $key }}" {{ $order->payment_status == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-success">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-cart me-2"></i>Order Items
                </h5>
            </div>
            <div class="card-body p-0">
                @foreach($order->items as $item)
                <div class="p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="row align-items-center">
                        <div class="col-md-2 mb-3 mb-md-0">
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}"
                                 class="img-fluid rounded" style="height: 80px; object-fit: cover;"
                                 onerror="this.src='https://images.unsplash.com/photo-1558857563-b371033873b8?w=200'">
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="mb-1">{{ $item->product_name }}</h6>
                            <p class="text-muted mb-0 small">
                                <span class="badge bg-light text-dark">{{ $item->size_label }}</span>
                                <span class="badge bg-light text-dark">{{ $item->sugar_level_label }}</span>
                                <span class="badge bg-light text-dark">{{ $item->ice_level_label }}</span>
                            </p>
                            @if(!empty($item->addons_list))
                            <p class="text-muted mb-0 small mt-1">
                                <strong>Add-ons:</strong>
                                {{ collect($item->addons_list)->pluck('name')->implode(', ') }}
                            </p>
                            @endif
                            @if($item->special_instructions)
                            <p class="text-muted mb-0 small mt-1">
                                <strong>Note:</strong> {{ $item->special_instructions }}
                            </p>
                            @endif
                        </div>
                        <div class="col-md-2 text-md-center mb-3 mb-md-0">
                            <span class="text-muted">x{{ $item->quantity }}</span>
                        </div>
                        <div class="col-md-2 text-md-end">
                            <span class="fw-bold text-primary">{{ $item->formatted_subtotal }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Delivery Info -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-truck me-2"></i>Delivery Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <span class="text-muted small">Customer Name</span>
                        <p class="mb-0 fw-bold">{{ $order->customer_name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <span class="text-muted small">Phone Number</span>
                        <p class="mb-0 fw-bold">{{ $order->customer_phone }}</p>
                    </div>
                    <div class="col-12">
                        <span class="text-muted small">Delivery Address</span>
                        <p class="mb-0 fw-bold">{{ $order->delivery_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($order->notes)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-chat-left-text me-2"></i>Order Notes
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $order->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Order Summary -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>Order Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <span>{{ $order->formatted_subtotal }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tax</span>
                    <span>{{ $order->formatted_tax }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <span class="h5">Total</span>
                    <span class="h5 text-primary">{{ $order->formatted_total }}</span>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.sticky-top {
    z-index: 100;
}
</style>
@endpush
