@extends('layouts.app')

@section('title', 'Sizes - Admin - Milk Tea Shop')

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
                        <a class="nav-link active" href="{{ route('admin.sizes.index') }}">
                            <i class="bi bi-rulers me-2"></i>Sizes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.orders.index') }}">
                            <i class="bi bi-receipt me-2"></i>Orders
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                <h1 class="h2"><i class="bi bi-rulers me-2"></i>Sizes</h1>
                <a href="{{ route('admin.sizes.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Add Size
                </a>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Display Name</th>
                                    <th>Price Adjustment</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sizes as $size)
                                    <tr>
                                        <td>{{ $size->name }}</td>
                                        <td>{{ $size->display_name }}</td>
                                        <td>{{ $size->formatted_price_adjustment ?: 'Base' }}</td>
                                        <td>
                                            @if($size->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.sizes.edit', $size) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.sizes.destroy', $size) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Are you sure?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No sizes found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
