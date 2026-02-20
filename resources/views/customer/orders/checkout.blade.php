@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<!-- Header -->
<section class="checkout-hero text-white py-5">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-2">Checkout</h1>
                <p class="lead mb-0">Complete your details and place your order in just one step.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('cart.index') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-arrow-left me-2"></i>Back to Cart
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Checkout -->
<section class="py-5">
    <div class="container">
        @include('partials.alerts')

        <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
            @csrf

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card checkout-card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Delivery Details</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="customer_name" class="form-label">Full Name *</label>
                                    <input
                                        type="text"
                                        id="customer_name"
                                        name="customer_name"
                                        class="form-control @error('customer_name') is-invalid @enderror"
                                        value="{{ old('customer_name', $user->name ?? '') }}"
                                        required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="customer_phone" class="form-label">Phone Number *</label>
                                    <input
                                        type="text"
                                        id="customer_phone"
                                        name="customer_phone"
                                        class="form-control @error('customer_phone') is-invalid @enderror"
                                        placeholder="+63 9xx xxx xxxx"
                                        value="{{ old('customer_phone', $user->phone ?? '') }}"
                                        required>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="delivery_address" class="form-label">Delivery Address *</label>
                                    <textarea
                                        id="delivery_address"
                                        name="delivery_address"
                                        rows="4"
                                        class="form-control @error('delivery_address') is-invalid @enderror"
                                        placeholder="House number, street, barangay, city"
                                        required>{{ old('delivery_address', $user->address ?? '') }}</textarea>
                                    @error('delivery_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="notes" class="form-label">Order Notes (Optional)</label>
                                    <textarea
                                        id="notes"
                                        name="notes"
                                        rows="3"
                                        class="form-control @error('notes') is-invalid @enderror"
                                        placeholder="Any special request for this order">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card checkout-card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="bi bi-credit-card-2-front me-2"></i>Payment Method</h5>
                        </div>
                        <div class="card-body p-4">
                            @php
                                $selectedPayment = old('payment_method', 'cash');
                            @endphp
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input class="btn-check payment-input" type="radio" name="payment_method" id="payment_cash" value="cash" {{ $selectedPayment === 'cash' ? 'checked' : '' }}>
                                    <label class="payment-option" for="payment_cash">
                                        <i class="bi bi-cash-coin"></i>
                                        <strong>Cash</strong>
                                        <span>Pay on delivery</span>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input class="btn-check payment-input" type="radio" name="payment_method" id="payment_card" value="card" {{ $selectedPayment === 'card' ? 'checked' : '' }}>
                                    <label class="payment-option" for="payment_card">
                                        <i class="bi bi-credit-card"></i>
                                        <strong>Card</strong>
                                        <span>Debit / Credit card</span>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input class="btn-check payment-input" type="radio" name="payment_method" id="payment_ewallet" value="ewallet" {{ $selectedPayment === 'ewallet' ? 'checked' : '' }}>
                                    <label class="payment-option" for="payment_ewallet">
                                        <i class="bi bi-phone"></i>
                                        <strong>E-Wallet</strong>
                                        <span>GCash / Maya</span>
                                    </label>
                                </div>
                            </div>
                            @error('payment_method')
                                <div class="text-danger small mt-3">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card summary-card border-0 shadow-sm sticky-top">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i>Order Summary</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="summary-items">
                                @foreach($cartItems as $item)
                                    @php
                                        $sizeLabel = ucfirst(str_replace('_', ' ', data_get($item, 'size', 'regular')));
                                        $sugarLabel = data_get($item, 'sugar_level', '100%');
                                        $iceLabel = ucfirst(str_replace('_', ' ', data_get($item, 'ice_level', 'regular')));
                                        $addonNames = collect(data_get($item, 'addons', []))
                                            ->map(function ($addon) {
                                                return is_object($addon) ? data_get($addon, 'name') : data_get($addon, 'name');
                                            })
                                            ->filter()
                                            ->implode(', ');
                                    @endphp
                                    <div class="summary-item d-flex gap-3">
                                        <img
                                            src="{{ data_get($item, 'product.image_url') }}"
                                            alt="{{ data_get($item, 'product.name', 'Product') }}"
                                            class="summary-item-img"
                                            onerror="this.src='{{ asset('images/default-product.svg') }}'">

                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="mb-1">{{ data_get($item, 'product.name', 'Product') }}</h6>
                                                <span class="fw-semibold text-dark">${{ number_format((float) data_get($item, 'subtotal', 0), 2) }}</span>
                                            </div>
                                            <p class="small text-muted mb-1">Qty {{ (int) data_get($item, 'quantity', 1) }} | {{ $sizeLabel }}</p>
                                            <p class="small text-muted mb-0">{{ $sugarLabel }} sugar, {{ $iceLabel }} ice</p>
                                            @if($addonNames)
                                                <p class="small text-muted mb-0">+ {{ $addonNames }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-semibold">${{ number_format((float) $subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Tax (8%)</span>
                                <span class="fw-semibold">${{ number_format((float) $tax, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between total-row mb-4">
                                <span>Total</span>
                                <span>${{ number_format((float) $total, 2) }}</span>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 place-order-btn">
                                <i class="bi bi-bag-check me-2"></i>Place Order
                            </button>
                            <p class="text-muted small text-center mb-0 mt-3">
                                <i class="bi bi-shield-lock me-1"></i>Secure checkout. Your information is protected.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('styles')
<style>
.checkout-hero {
    position: relative;
    overflow: hidden;
    background: linear-gradient(120deg, #0d6efd 0%, #1f8cff 50%, #58b0ff 100%);
}

.checkout-hero::before,
.checkout-hero::after {
    content: '';
    position: absolute;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.16);
}

.checkout-hero::before {
    width: 240px;
    height: 240px;
    top: -110px;
    right: 8%;
}

.checkout-hero::after {
    width: 180px;
    height: 180px;
    bottom: -90px;
    left: 12%;
}

.checkout-card,
.summary-card {
    border-radius: 16px;
}

.summary-card {
    top: 100px;
}

.payment-option {
    border: 1.5px solid #e4e8ef;
    border-radius: 12px;
    padding: 14px;
    background: #fff;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    gap: 4px;
    transition: all 0.2s ease;
    height: 100%;
}

.payment-option i {
    font-size: 1.2rem;
    color: #0d6efd;
}

.payment-option strong {
    font-size: 0.98rem;
    color: #1f2937;
}

.payment-option span {
    font-size: 0.82rem;
    color: #6b7280;
}

.payment-input:checked + .payment-option {
    border-color: #0d6efd;
    box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
    transform: translateY(-2px);
}

.summary-items {
    max-height: 340px;
    overflow: auto;
    padding-right: 4px;
}

.summary-item {
    padding-bottom: 12px;
    margin-bottom: 12px;
    border-bottom: 1px solid #eef1f5;
}

.summary-item:last-child {
    margin-bottom: 0;
    border-bottom: none;
}

.summary-item-img {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    object-fit: cover;
    background: #f4f7fb;
}

.total-row {
    font-size: 1.1rem;
    font-weight: 700;
    color: #111827;
}

.place-order-btn {
    border-radius: 12px;
    padding-top: 0.8rem;
    padding-bottom: 0.8rem;
}

@media (max-width: 991.98px) {
    .summary-card {
        position: static !important;
        top: auto !important;
    }
}
</style>
@endpush
