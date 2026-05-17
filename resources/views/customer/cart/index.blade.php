@extends('layouts.app')

@section('title', 'Shopping Cart - Milk Tea Shop')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-cart3 me-2"></i>Shopping Cart</h2>
        </div>
    </div>

    @if(count($cartItems) > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Cart Items ({{ count($cartItems) }})</h5>
                        <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                    onclick="return confirm('Are you sure you want to clear your cart?')">
                                <i class="bi bi-trash me-1"></i>Clear Cart
                            </button>
                        </form>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Size</th>
                                        <th>Add-ons</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item['product']->image_url }}" 
                                                         alt="{{ $item['product']->name }}"
                                                         class="rounded me-3"
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-0">{{ $item['product']->name }}</h6>
                                                        <small class="text-muted">
                                                            Sugar: {{ $item['sugar_level'] }} | Ice: {{ ucfirst(str_replace('_', ' ', $item['ice_level'])) }}
                                                        </small>
                                                        <br>
                                                        @if($item['special_instructions'])
                                                            <small class="text-muted">
                                                                <i class="bi bi-chat-left-text"></i> {{ Str::limit($item['special_instructions'], 20) }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $item['size']->display_name }}</td>
                                            <td>
                                                @if($item['addOns']->count() > 0)
                                                    <small>{{ $item['addOns']->pluck('name')->join(', ') }}</small>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </td>
                                            <td>PHP {{ number_format($item['unit_price'], 2) }}</td>
                                            <td>
                                                <form action="{{ route('cart.update', $item['key']) }}" 
                                                      method="POST" class="d-flex align-items-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="quantity" 
                                                           value="{{ $item['quantity'] }}" 
                                                           min="1" max="50"
                                                           class="form-control form-control-sm" 
                                                           style="width: 60px;">
                                                    <button type="submit" class="btn btn-sm btn-outline-primary ms-1">
                                                        <i class="bi bi-check"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="fw-bold">PHP {{ number_format($item['total'], 2) }}</td>
                                            <td>
                                                <form action="{{ route('cart.destroy', $item['key']) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>PHP {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (8%)</span>
                            <span>PHP {{ number_format($tax, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold fs-5 text-primary">PHP {{ number_format($total, 2) }}</span>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                            </a>
                            <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-cart-x display-1 text-muted"></i>
                        <h4 class="mt-4">Your cart is empty</h4>
                        <p class="text-muted">Looks like you haven't added any items yet.</p>
                        <a href="{{ route('menu.index') }}" class="btn btn-primary">
                            <i class="bi bi-menu-button-wide me-2"></i>Browse Menu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
