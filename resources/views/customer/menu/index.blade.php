@extends('layouts.app')

@section('title', 'Menu - Milk Tea Shop')

@section('content')
<div class="container py-4">
    <!-- Hero Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="menu-hero">
                <div class="menu-hero-content">
                    <span class="menu-hero-kicker">
                        <i class="bi bi-stars me-2"></i>Freshly mixed drinks
                    </span>
                    <h1>Our Menu</h1>
                    <p>Choose your favorite milk tea, fruit tea, or coffee and customize it just the way you like.</p>
                    <div class="menu-hero-tags">
                        <span><i class="bi bi-cup-straw"></i> Crafted daily</span>
                        <span><i class="bi bi-droplet-half"></i> Sugar control</span>
                        <span><i class="bi bi-bag-check"></i> Easy ordering</span>
                    </div>
                </div>
                <div class="menu-hero-visual" aria-hidden="true">
                    <div class="menu-hero-straw"></div>
                    <div class="menu-hero-cup">
                        <span class="pearl pearl-one"></span>
                        <span class="pearl pearl-two"></span>
                        <span class="pearl pearl-three"></span>
                        <span class="pearl pearl-four"></span>
                    </div>
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
                        {{ Str::headline($cat) }}
                    </a>
                @endforeach
            </div>
        </div>
        <div class="col-md-6">
            <form action="{{ route('menu.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control" placeholder="Search products..." 
                       value="{{ $search }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
        @if($search !== '')
            <div class="col-12 mt-3">
                <div class="search-result-note">
                    <i class="bi bi-search me-2"></i>
                    Showing results for <strong>{{ $search }}</strong>
                    <a href="{{ route('menu.index') }}" class="ms-2">Clear</a>
                </div>
            </div>
        @endif
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

@push('styles')
<style>
    .menu-hero {
        position: relative;
        min-height: 245px;
        overflow: hidden;
        border-radius: 8px;
        background:
            linear-gradient(115deg, rgba(68, 37, 17, 0.96) 0%, rgba(122, 72, 33, 0.94) 52%, rgba(211, 157, 88, 0.94) 100%),
            repeating-linear-gradient(135deg, rgba(255, 255, 255, 0.16) 0 1px, transparent 1px 24px);
        box-shadow: 0 18px 42px rgba(77, 42, 18, 0.18);
        color: #fffaf4;
        padding: 2.25rem;
    }

    .menu-hero::before {
        content: "";
        position: absolute;
        inset: auto -8% -42px -8%;
        height: 112px;
        background: rgba(255, 248, 235, 0.16);
        transform: rotate(-2deg);
    }

    .menu-hero-content {
        position: relative;
        z-index: 2;
        max-width: 620px;
    }

    .menu-hero-kicker {
        display: inline-flex;
        align-items: center;
        margin-bottom: 0.8rem;
        color: #ffe5b8;
        font-weight: 700;
        letter-spacing: 0;
    }

    .menu-hero h1 {
        margin: 0 0 0.65rem;
        font-size: 2.45rem;
        font-weight: 800;
        line-height: 1.05;
        letter-spacing: 0;
    }

    .menu-hero p {
        max-width: 520px;
        margin-bottom: 1.25rem;
        color: rgba(255, 250, 244, 0.9);
        font-size: 1.05rem;
    }

    .menu-hero-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
    }

    .menu-hero-tags span {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.28);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.13);
        color: #fffaf4;
        font-size: 0.92rem;
        font-weight: 600;
    }

    .menu-hero-visual {
        position: absolute;
        right: 2rem;
        bottom: 0;
        width: 230px;
        height: 225px;
        pointer-events: none;
    }

    .menu-hero-cup {
        position: absolute;
        right: 34px;
        bottom: 18px;
        width: 132px;
        height: 164px;
        overflow: hidden;
        border: 8px solid rgba(255, 253, 246, 0.92);
        border-top-width: 12px;
        border-radius: 12px 12px 34px 34px;
        background:
            linear-gradient(180deg, rgba(255, 232, 190, 0.98) 0 30%, rgba(168, 103, 49, 0.96) 31% 100%);
        box-shadow: 0 18px 28px rgba(34, 18, 8, 0.28);
        transform: perspective(220px) rotateX(-5deg) rotate(-2deg);
    }

    .menu-hero-cup::before {
        content: "";
        position: absolute;
        top: 44px;
        left: -18px;
        width: 170px;
        height: 36px;
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(-8deg);
    }

    .menu-hero-cup::after {
        content: "";
        position: absolute;
        top: 18px;
        left: 28px;
        width: 24px;
        height: 126px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(8deg);
    }

    .menu-hero-straw {
        position: absolute;
        right: 48px;
        top: 10px;
        width: 18px;
        height: 180px;
        border-radius: 999px;
        background: #2b2119;
        transform: rotate(-13deg);
        box-shadow: inset 5px 0 rgba(255, 255, 255, 0.22);
    }

    .menu-hero-straw::before {
        content: "";
        position: absolute;
        top: -8px;
        right: -24px;
        width: 78px;
        height: 16px;
        border-radius: 999px;
        background: #2b2119;
        transform: rotate(8deg);
    }

    .pearl {
        position: absolute;
        bottom: 22px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: rgba(42, 24, 15, 0.86);
        box-shadow: inset -3px -3px rgba(0, 0, 0, 0.12);
    }

    .pearl-one { left: 24px; }
    .pearl-two { left: 54px; bottom: 12px; }
    .pearl-three { right: 38px; }
    .pearl-four { right: 14px; bottom: 38px; }

    .search-result-note {
        display: inline-flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.2rem;
        padding: 0.55rem 0.8rem;
        border-radius: 8px;
        background: #fff7eb;
        color: #5f351b;
        font-size: 0.95rem;
        border: 1px solid rgba(139, 90, 43, 0.18);
    }

    @media (max-width: 767.98px) {
        .menu-hero {
            min-height: 230px;
            padding: 1.35rem;
        }

        .menu-hero h1 {
            font-size: 2rem;
        }

        .menu-hero p {
            max-width: 76%;
            font-size: 0.98rem;
        }

        .menu-hero-tags {
            max-width: 72%;
        }

        .menu-hero-tags span {
            padding: 0.38rem 0.58rem;
            font-size: 0.82rem;
        }

        .menu-hero-visual {
            right: -28px;
            bottom: -12px;
            opacity: 0.74;
            transform: scale(0.86);
        }
    }

    @media (max-width: 430px) {
        .menu-hero p,
        .menu-hero-tags {
            max-width: 68%;
        }

        .menu-hero-visual {
            right: -56px;
            opacity: 0.58;
        }
    }
</style>
@endpush
