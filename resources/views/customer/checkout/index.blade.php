@extends('layouts.app')

@section('title', 'Checkout - Milk Tea Shop')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-credit-card me-2"></i>Checkout</h2>
        </div>
    </div>

    <div class="row">
        <!-- Checkout Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Customer Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label">Full Name *</label>
                                <input type="text" 
                                       class="form-control @error('customer_name') is-invalid @enderror" 
                                       id="customer_name" 
                                       name="customer_name" 
                                       value="{{ old('customer_name', auth()->user()->name) }}" 
                                       required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact_number" class="form-label">Contact Number *</label>
                                <input type="text" 
                                       class="form-control @error('contact_number') is-invalid @enderror" 
                                       id="contact_number" 
                                       name="contact_number" 
                                       value="{{ old('contact_number', auth()->user()->phone) }}" 
                                       required>
                                @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="2" 
                                      required>{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3"><i class="bi bi-gear me-2"></i>Order Options</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">Payment Method *</label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" 
                                        id="payment_method" 
                                        name="payment_method" 
                                        required>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>
                                        Cash on Pickup
                                    </option>
                                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>
                                        Credit/Debit Card
                                    </option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pickup_method" class="form-label">Pickup Method *</label>
                                <select class="form-select @error('pickup_method') is-invalid @enderror" 
                                        id="pickup_method" 
                                        name="pickup_method" 
                                        required>
                                    <option value="in_store" {{ old('pickup_method') == 'in_store' ? 'selected' : '' }}>
                                        In-Store Pickup
                                    </option>
                                    <option value="drive_thru" {{ old('pickup_method') == 'drive_thru' ? 'selected' : '' }}>
                                        Drive-Thru
                                    </option>
                                </select>
                                @error('pickup_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="pickup_time" class="form-label">Preferred Pickup Time (Optional)</label>
                            <input type="datetime-local" 
                                   class="form-control @error('pickup_time') is-invalid @enderror" 
                                   id="pickup_time" 
                                   name="pickup_time"
                                   value="{{ old('pickup_time') }}">
                            @error('pickup_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="2"
                                      placeholder="Any special instructions for your order...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Place Order
                            </button>
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Cart
                            </a>
                        </div>
                    </form>
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
                    <!-- Cart Items -->
                    <div class="mb-3">
                        @foreach($cartItems as $item)
                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                <div>
                                    <small class="fw-bold">{{ $item['product']->name }}</small>
                                    <br>
                                    <small class="text-muted">
                                        {{ $item['size']->display_name }}
                                        | Sugar: {{ $item['sugar_level'] }}
                                        | Ice: {{ ucfirst(str_replace('_', ' ', $item['ice_level'])) }}
                                        @if($item['addOns']->count() > 0)
                                            + {{ $item['addOns']->pluck('name')->join(', ') }}
                                        @endif
                                        x{{ $item['quantity'] }}
                                    </small>
                                </div>
                                <small class="text-end">PHP {{ number_format($item['total'], 2) }}</small>
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>PHP {{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (8%)</span>
                        <span>PHP {{ number_format($tax, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold fs-5">Total</span>
                        <span class="fw-bold fs-5 text-primary">PHP {{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
