@extends('layouts.app')

@section('title', $product->name . ' - Milk Tea Shop')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Product Image -->
        <div class="col-lg-5 mb-4">
            <div class="card">
                <img src="{{ $product->image_url }}" 
                     class="card-img-top" 
                     alt="{{ $product->name }}"
                     style="max-height: 400px; object-fit: cover;">
            </div>
        </div>

        <!-- Customization Form -->
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="mb-0">{{ $product->name }}</h4>
                    <p class="text-muted mb-0">{{ $product->description }}</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <!-- Size Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-rulers me-2"></i>Size
                            </label>
                            <div class="row g-2">
                                @foreach($sizes as $size)
                                    <div class="col-md-4">
                                        <div class="form-check card p-3">
                                            <input class="form-check-input" type="radio" 
                                                   name="size_id" 
                                                   id="size_{{ $size->id }}" 
                                                   value="{{ $size->id }}"
                                                   {{ $loop->first ? 'checked' : '' }}
                                                   required>
                                            <label class="form-check-label w-100" for="size_{{ $size->id }}">
                                                <div class="d-flex justify-content-between">
                                                    <span>{{ $size->display_name }}</span>
                                                    <span class="text-primary">
                                                        {{ $size->formatted_price_adjustment ?: 'Base' }}
                                                    </span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('size_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sugar Level -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-droplet me-2"></i>Sugar Level
                            </label>
                            <select name="sugar_level" class="form-select">
                                <option value="0%">0% (No Sugar)</option>
                                <option value="25%">25% (Low)</option>
                                <option value="50%" selected>50% (Half)</option>
                                <option value="75%">75% (Less)</option>
                                <option value="100%">100% (Normal)</option>
                            </select>
                        </div>

                        <!-- Ice Level -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-snow me-2"></i>Ice Level
                            </label>
                            <select name="ice_level" class="form-select">
                                <option value="no_ice">No Ice</option>
                                <option value="less_ice">Less Ice</option>
                                <option value="normal_ice" selected>Normal Ice</option>
                            </select>
                        </div>

                        <!-- Add-ons -->
                        @if($addOns->count() > 0)
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-plus-circle me-2"></i>Add-ons (Optional)
                                </label>
                                <div class="row g-2">
                                    @foreach($addOns as $addOn)
                                        <div class="col-md-6">
                                            <div class="form-check card p-2">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="add_ons[]" 
                                                       id="addon_{{ $addOn->id }}" 
                                                       value="{{ $addOn->id }}">
                                                <label class="form-check-label w-100" for="addon_{{ $addOn->id }}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span>{{ $addOn->name }}</span>
                                                        <span class="text-primary small">{{ $addOn->formatted_price }}</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Quantity -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-123 me-2"></i>Quantity
                            </label>
                            <div class="input-group" style="max-width: 150px;">
                                <button type="button" class="btn btn-outline-secondary" onclick="decrementQty()">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" name="quantity" id="quantity" 
                                       class="form-control text-center" 
                                       value="1" min="1" max="50" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="incrementQty()">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            @error('quantity')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Special Instructions -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-chat-left-text me-2"></i>Special Instructions (Optional)
                            </label>
                            <textarea name="special_instructions" class="form-control" rows="2" 
                                      placeholder="Any special requests..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-cart-plus me-2"></i>Add to Cart
                            </button>
                            <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Menu
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function incrementQty() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) < 50) {
            input.value = parseInt(input.value) + 1;
        }
    }

    function decrementQty() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
</script>
@endpush
