@extends('layouts.app')

@section('title', 'Menu - Milk Tea Shop')

@section('content')
<div class="container py-4">
    <!-- Hero Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient text-white" style="background: linear-gradient(135deg, #8B5A2B 0%, #D4A574 100%);">
                <div class="card-body p-5 text-center">
                    <h1 class="display-5 mb-3"><i class="bi bi-cup-hot-fill me-3"></i>Our Menu</h1>
                    <p class="lead mb-0">Discover our delicious selection of milk teas and more!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="btn-group" role="group">
                <a href="{{ route('menu.index') }}" 
                   class="btn btn-outline-primary {{ !$category ? 'active' : '' }}">
                    All
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('menu.index', ['category' => $cat]) }}" 
                       class="btn btn-outline-primary {{ $category == $cat ? 'active' : '' }}">
                        {{ ucfirst($cat) }}
                    </a>
                @endforeach
            </div>
        </div>
        <div class="col-md-6">
            <form action="{{ route('menu.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control" placeholder="Search products..." 
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card product-card h-100">
                    <img src="{{ $product->image_url }}" 
                         class="card-img-top" 
                         alt="{{ $product->name }}"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $product->name }}</h5>
                            <span class="badge bg-primary">{{ $product->formatted_price }}</span>
                        </div>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit($product->description, 80) }}
                        </p>
                        <div class="d-grid mt-3">
                            <a href="{{ route('menu.show', $product) }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-2"></i>Customize & Add
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="mt-3 text-muted">No products found.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
