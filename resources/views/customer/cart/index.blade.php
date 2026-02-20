@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<!-- Page Header -->
<section class="page-header bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-2">Shopping Cart</h1>
                <p class="lead mb-0">Review your items and proceed to checkout</p>
            </div>
        </div>
    </div>
</section>

<!-- Cart Section -->
<section class="py-5">
    <div class="container">
        @include('partials.alerts')

        @if(count($cartItems) > 0)
        <div class="row g-4">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <!-- responsive header -->
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                            <h5 class="mb-0">
                                <i class="bi bi-cart3 me-2"></i>Cart Items ({{ count($cartItems) }})
                            </h5>

                            <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100 w-sm-auto"
                                        onclick="return confirm('Are you sure you want to clear your cart?')">
                                    <i class="bi bi-trash me-1"></i>Clear Cart
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @foreach($cartItems as $item)
                        <div class="cart-item p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="row align-items-center g-3">
                                <!-- image -->
                                <div class="col-4 col-sm-3 col-md-2">
                                    <img
                                        src="{{ data_get($item, 'product.image_url') }}"
                                        alt="{{ data_get($item, 'product.name', 'Product') }}"
                                        class="img-fluid rounded cart-img"
                                        onerror="this.src='https://images.unsplash.com/photo-1558857563-b371033873b8?w=200'">
                                </div>

                                <!-- name + badges -->
                                <div class="col-8 col-sm-9 col-md-4">
                                    <h5 class="mb-1">{{ data_get($item, 'product.name', 'Product') }}</h5>

                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="badge bg-light text-dark">
                                            {{ data_get($item, 'size_label', 'Regular') }}
                                        </span>
                                        <span class="badge bg-light text-dark">
                                            {{ data_get($item, 'sugar_level_label', '100% Sugar') }}
                                        </span>
                                        <span class="badge bg-light text-dark">
                                            {{ data_get($item, 'ice_level_label', 'Regular Ice') }}
                                        </span>
                                    </div>

                                    @if(!empty(data_get($item, 'addons')))
                                        <p class="text-muted mb-0 small mt-2">
                                            <strong>Add-ons:</strong>
                                            {{ collect(data_get($item, 'addons', []))->pluck('name')->implode(', ') }}
                                        </p>
                                    @endif

                                    @if(!empty(data_get($item, 'special_instructions')))
                                        <p class="text-muted mb-0 small mt-1">
                                            <strong>Note:</strong> {{ data_get($item, 'special_instructions') }}
                                        </p>
                                    @endif
                                </div>

                                <!-- quantity -->
                                <div class="col-12 col-md-3">
                                    <form action="{{ route('cart.update', data_get($item, 'key')) }}" method="POST" class="d-flex flex-wrap align-items-center gap-2">
                                        @csrf
                                        @method('PATCH')

                                        <div class="input-group input-group-sm cart-qty-group">
                                            <button type="button" class="btn btn-outline-secondary decrease-qty">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input
                                                type="number"
                                                class="form-control text-center qty-input"
                                                name="quantity"
                                                value="{{ (int) data_get($item, 'quantity', 1) }}"
                                                min="1"
                                                max="10">
                                            <button type="button" class="btn btn-outline-secondary increase-qty">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>

                                        <button type="submit" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-arrow-repeat me-1"></i>Update
                                        </button>
                                    </form>
                                </div>

                                <!-- price -->
                                <div class="col-8 col-md-2 text-start text-md-end">
                                    <h5 class="mb-0 text-primary">
                                        ${{ number_format((float) data_get($item, 'subtotal', 0), 2) }}
                                    </h5>
                                    <small class="text-muted">
                                        ${{ number_format((float) data_get($item, 'unit_price', 0) + (float) data_get($item, 'addons_total', 0), 2) }} each
                                    </small>
                                </div>

                                <!-- remove -->
                                <div class="col-4 col-md-1 text-end">
                                    <form action="{{ route('cart.remove', data_get($item, 'key')) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Remove this item?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('products.index') }}" class="btn btn-outline-primary w-100 w-sm-auto">
                    <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top sticky-top-desktop" style="top: 100px;">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-receipt me-2"></i>Order Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>${{ number_format((float) $subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (8%)</span>
                            <span>${{ number_format((float) $tax, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5">Total</span>
                            <span class="h5 text-primary">${{ number_format((float) $total, 2) }}</span>
                        </div>

                        <a href="{{ route('orders.checkout') }}" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
            <h3 class="text-muted">Your cart is empty</h3>
            <p class="text-muted mb-4">Looks like you haven't added any items yet.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-grid me-2"></i>Browse Menu
            </a>
        </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.decrease-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.closest('.input-group').querySelector('.qty-input');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        });
    });

    document.querySelectorAll('.increase-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.closest('.input-group').querySelector('.qty-input');
            if (parseInt(input.value) < 10) {
                input.value = parseInt(input.value) + 1;
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.page-header {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.cart-item {
    transition: background-color 0.2s ease;
}
.cart-item:hover {
    background-color: #f8f9fa;
}

/* image sizing */
.cart-img {
    height: 80px;
    width: 100%;
    object-fit: cover;
}

/* qty group responsive */
.cart-qty-group {
    width: 140px;
}

/* Disable sticky summary on mobile/tablet so it won't squeeze */
@media (max-width: 991.98px) {
    .sticky-top-desktop {
        position: static !important;
        top: auto !important;
    }
}

/* Mobile polish */
@media (max-width: 575.98px) {
    .cart-item {
        padding: 1rem !important;
    }
    .cart-qty-group {
        width: 100%;
    }
}

.sticky-top {
    z-index: 100;
}
</style>
@endpush
