@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' - Admin - Milk Tea Shop')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block admin-sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.products.index') }}">
                            <i class="bi bi-box-seam me-2"></i>Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.addons.index') }}">
                            <i class="bi bi-plus-circle me-2"></i>Add-ons
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.sizes.index') }}">
                            <i class="bi bi-rulers me-2"></i>Sizes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.orders.index') }}">
                            <i class="bi bi-receipt me-2"></i>Orders
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                <div>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
                        <i class="bi bi-arrow-left me-2"></i>Back to Orders
                    </a>
                    <h1 class="h2 mb-0"><i class="bi bi-receipt me-2"></i>Order {{ $order->order_number }}</h1>
                </div>
            </div>

            <div class="row">
                <!-- Order Info -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Order Items</h5>
                        </div>
                        <div class="card-body">
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
                    <!-- Customer Info -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="bi bi-person me-2"></i>Customer Information</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>{{ $order->customer_name ?? $order->user->name }}</strong></p>
                            <p class="mb-1 text-muted">{{ $order->user->email }}</p>
                            <p class="mb-1 text-muted">{{ $order->contact_number ?? $order->user->phone }}</p>
                            <p class="mb-0 text-muted">{{ $order->delivery_address ?? $order->user->address }}</p>
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Order Status</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="mb-3">
                                @csrf
                                @method('PATCH')
                                <label class="form-label">Update Status</label>
                                <div class="input-group">
                                    <select name="status" class="form-select">
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>

                            <form action="{{ route('admin.orders.payment', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <label class="form-label">Payment Status</label>
                                <div class="input-group">
                                    <select name="payment_status" class="form-select">
                                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                        <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="card">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="bi bi-list me-2"></i>Order Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted d-block">Order Date</small>
                                <span>{{ $order->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Payment Method</small>
                                <span>{{ ucfirst($order->payment_method) }}</span>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Payment Status</small>
                                <span class="badge badge-{{ $order->payment_status }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Pickup Method</small>
                                <span>{{ ucfirst(str_replace('_', ' ', $order->pickup_method)) }}</span>
                            </div>
                            @if($order->pickup_time)
                                <div class="mb-0">
                                    <small class="text-muted d-block">Pickup Time</small>
                                    <span>{{ $order->pickup_time->format('M d, Y h:i A') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .admin-sidebar {
        min-height: calc(100vh - 56px);
        background-color: #343a40;
    }
    .admin-sidebar .nav-link {
        color: rgba(255,255,255,0.75);
        padding: 0.75rem 1rem;
    }
    .admin-sidebar .nav-link:hover,
    .admin-sidebar .nav-link.active {
        color: #fff;
        background-color: rgba(255,255,255,0.1);
    }
</style>
@endpush
