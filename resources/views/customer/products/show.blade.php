@extends('layouts.app')

@section('title', $product->name)

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Menu</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </div>
</nav>

<!-- Product Details -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Product Image -->
            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow-sm">
                    <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}"
                         onerror="this.src='https://images.unsplash.com/photo-1558857563-b371033873b8?w=600'"
                         style="height: 400px; object-fit: cover;">
                </div>
            </div>

            <!-- Product Info & Customization -->
            <div class="col-lg-7">
                <span class="badge bg-primary mb-2">{{ $product->category_label }}</span>
                <h1 class="mb-3">{{ $product->name }}</h1>
                <p class="text-muted mb-4">{{ $product->description }}</p>

                <form action="{{ route('cart.add') }}" method="POST" id="orderForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <!-- Size Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Size</label>
                        <div class="row g-2">
                            @foreach($product->available_sizes ?? ['small', 'medium', 'large'] as $size)
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="size" id="size_{{ $size }}" 
                                       value="{{ $size }}" {{ $size == 'medium' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary w-100" for="size_{{ $size }}">
                                    {{ ucfirst($size) }}
                                    <small class="d-block">
                                        ${{ number_format($product->getPriceForSize($size), 2) }}
                                    </small>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Sugar Level -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Sugar Level</label>
                        <div class="row g-2">
                            @foreach($product->available_sugar_levels ?? ['0%', '30%', '50%', '70%', '100%'] as $sugar)
                            <div class="col">
                                <input type="radio" class="btn-check" name="sugar_level" id="sugar_{{ str_replace('%', '', $sugar) }}" 
                                       value="{{ $sugar }}" {{ $sugar == '100%' ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary w-100" for="sugar_{{ str_replace('%', '', $sugar) }}">
                                    {{ $sugar }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Ice Level -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Ice Level</label>
                        <div class="row g-2">
                            @php
                            $iceLabels = ['no_ice' => 'No Ice', 'less' => 'Less Ice', 'regular' => 'Regular', 'extra' => 'Extra Ice'];
                            @endphp
                            @foreach($product->available_ice_levels ?? ['no_ice', 'less', 'regular', 'extra'] as $ice)
                            <div class="col-3">
                                <input type="radio" class="btn-check" name="ice_level" id="ice_{{ $ice }}" 
                                       value="{{ $ice }}" {{ $ice == 'regular' ? 'checked' : '' }}>
                                <label class="btn btn-outline-info w-100" for="ice_{{ $ice }}">
                                    {{ $iceLabels[$ice] ?? ucfirst($ice) }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Add-ons -->
                    @if($addons->count() > 0)
                    <div class="mb-4">
                        <label class="form-label fw-bold">Add-ons</label>
                        <div class="row g-2">
                            @foreach($addons as $addon)
                            <div class="col-md-6">
                                <div class="form-check border rounded p-3">
                                    <input class="form-check-input addon-checkbox" type="checkbox" 
                                           name="addons[]" value="{{ $addon->id }}" 
                                           id="addon_{{ $addon->id }}" data-price="{{ $addon->price }}">
                                    <label class="form-check-label d-flex justify-content-between w-100" for="addon_{{ $addon->id }}">
                                        <span>{{ $addon->name }}</span>
                                        <span class="text-primary">+${{ number_format($addon->price, 2) }}</span>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Special Instructions -->
                    <div class="mb-4">
                        <label for="special_instructions" class="form-label fw-bold">Special Instructions</label>
                        <textarea class="form-control" id="special_instructions" name="special_instructions" 
                                  rows="2" placeholder="Any special requests?"></textarea>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Quantity</label>
                        <div class="input-group" style="width: 150px;">
                            <button type="button" class="btn btn-outline-secondary" id="decreaseQty">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" class="form-control text-center" name="quantity" id="quantity" 
                                   value="1" min="1" max="10" readonly>
                            <button type="button" class="btn btn-outline-secondary" id="increaseQty">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Price Display -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0">Total Price:</span>
                                <span class="h3 mb-0 text-primary" id="totalPrice">
                                    ${{ number_format($product->base_price, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Add to Cart Button -->
                    <div class="d-grid gap-2 d-md-flex">
                        <button type="submit" class="btn btn-primary btn-lg flex-fill">
                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>Back to Menu
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="mt-5 pt-5 border-top">
            <h3 class="mb-4">You May Also Like</h3>
            <div class="row g-4">
                @foreach($relatedProducts as $related)
                <div class="col-6 col-md-3">
                    <div class="card product-card h-100 border-0 shadow-sm">
                        <div class="product-image-wrapper" style="height: 180px;">
                            <img src="{{ $related->image_url }}" class="card-img-top" alt="{{ $related->name }}"
                                 onerror="this.src='https://images.unsplash.com/photo-1558857563-b371033873b8?w=400'">
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">{{ $related->name }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">{{ $related->formatted_price }}</span>
                                <a href="{{ route('products.show', $related) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const basePrice = {{ $product->base_price }};
    const sizeMultipliers = {
        'small': 0.85,
        'medium': 1.0,
        'large': 1.25
    };

    function calculateTotal() {
        const size = document.querySelector('input[name="size"]:checked').value;
        const quantity = parseInt(document.getElementById('quantity').value);
        
        let unitPrice = basePrice * sizeMultipliers[size];
        
        // Add addon prices
        document.querySelectorAll('.addon-checkbox:checked').forEach(checkbox => {
            unitPrice += parseFloat(checkbox.dataset.price);
        });
        
        const total = unitPrice * quantity;
        document.getElementById('totalPrice').textContent = '$' + total.toFixed(2);
    }

    // Size change
    document.querySelectorAll('input[name="size"]').forEach(radio => {
        radio.addEventListener('change', calculateTotal);
    });

    // Addon change
    document.querySelectorAll('.addon-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', calculateTotal);
    });

    // Quantity buttons
    document.getElementById('decreaseQty').addEventListener('click', function() {
        const qtyInput = document.getElementById('quantity');
        if (parseInt(qtyInput.value) > 1) {
            qtyInput.value = parseInt(qtyInput.value) - 1;
            calculateTotal();
        }
    });

    document.getElementById('increaseQty').addEventListener('click', function() {
        const qtyInput = document.getElementById('quantity');
        if (parseInt(qtyInput.value) < 10) {
            qtyInput.value = parseInt(qtyInput.value) + 1;
            calculateTotal();
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.product-card {
    transition: all 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.product-image-wrapper {
    position: relative;
    overflow: hidden;
}

.product-image-wrapper img {
    transition: transform 0.3s ease;
}

.product-card:hover .product-image-wrapper img {
    transform: scale(1.05);
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.btn-check:checked + .btn-outline-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.btn-check:checked + .btn-outline-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-check:checked + .btn-outline-info {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: white;
}
</style>
@endpush
