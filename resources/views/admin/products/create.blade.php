@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="bi bi-plus-circle me-2"></i>Add Product
    </h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Products
    </a>
</div>

<!-- Product Form -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-lg-8">
                    <!-- Basic Information -->
                    <div class="mb-4">
                        <h5 class="mb-3">Basic Information</h5>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="base_price" class="form-label">Base Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" min="0" 
                                           class="form-control @error('base_price') is-invalid @enderror" 
                                           id="base_price" name="base_price" value="{{ old('base_price') }}" required>
                                </div>
                                @error('base_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    @foreach($categories as $key => $name)
                                        <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Customization Options -->
                    <div class="mb-4">
                        <h5 class="mb-3">Customization Options</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Available Sizes *</label>
                            <div class="row g-2">
                                @foreach($sizes as $size)
                                <div class="col-4">
                                    <div class="form-check border rounded p-3">
                                        <input class="form-check-input" type="checkbox" 
                                               name="available_sizes[]" value="{{ $size }}" 
                                               id="size_{{ $size }}" {{ in_array($size, old('available_sizes', ['small', 'medium', 'large'])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="size_{{ $size }}">
                                            {{ ucfirst($size) }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('available_sizes')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Available Sugar Levels *</label>
                            <div class="row g-2">
                                @foreach($sugarLevels as $sugar)
                                <div class="col">
                                    <div class="form-check border rounded p-3">
                                        <input class="form-check-input" type="checkbox" 
                                               name="available_sugar_levels[]" value="{{ $sugar }}" 
                                               id="sugar_{{ str_replace('%', '', $sugar) }}" {{ in_array($sugar, old('available_sugar_levels', ['0%', '30%', '50%', '70%', '100%'])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sugar_{{ str_replace('%', '', $sugar) }}">
                                            {{ $sugar }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('available_sugar_levels')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Available Ice Levels *</label>
                            <div class="row g-2">
                                @php
                                $iceLabels = ['no_ice' => 'No Ice', 'less' => 'Less Ice', 'regular' => 'Regular', 'extra' => 'Extra Ice'];
                                @endphp
                                @foreach($iceLevels as $ice)
                                <div class="col-3">
                                    <div class="form-check border rounded p-3">
                                        <input class="form-check-input" type="checkbox" 
                                               name="available_ice_levels[]" value="{{ $ice }}" 
                                               id="ice_{{ $ice }}" {{ in_array($ice, old('available_ice_levels', ['no_ice', 'less', 'regular', 'extra'])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ice_{{ $ice }}">
                                            {{ $iceLabels[$ice] }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('available_ice_levels')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Product Image -->
                    <div class="mb-4">
                        <h5 class="mb-3">Product Image</h5>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <small class="text-muted">Recommended size: 600x600px</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <img id="imagePreview" src="{{ asset('images/default-product.svg') }}" 
                                 class="img-fluid rounded w-100" alt="Preview"
                                 style="max-height: 320px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <h5 class="mb-3">Status</h5>
                        
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" id="is_active" 
                                   name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Create Product
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-lg">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const input = document.getElementById('image');
    const preview = document.getElementById('imagePreview');

    if (!input || !preview) {
        return;
    }

    input.addEventListener('change', (event) => {
        const [file] = event.target.files || [];
        if (!file) {
            return;
        }

        const reader = new FileReader();
        reader.onload = (loadEvent) => {
            preview.src = loadEvent.target.result;
        };
        reader.readAsDataURL(file);
    });
})();
</script>
@endpush
