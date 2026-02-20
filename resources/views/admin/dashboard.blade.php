@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </h2>
    <span class="text-muted">{{ now()->format('l, F d, Y') }}</span>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <!-- Today's Orders -->
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-primary">
                        <i class="bi bi-bag text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Today's Orders</h6>
                        <h3 class="mb-0">{{ $todayOrders }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-warning">
                        <i class="bi bi-hourglass-split text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Pending Orders</h6>
                        <h3 class="mb-0">{{ $pendingOrders }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Revenue -->
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-success">
                        <i class="bi bi-currency-dollar text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Today's Revenue</h6>
                        <h3 class="mb-0">${{ number_format($todayRevenue, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Products -->
    <div class="col-md-6 col-lg-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-info">
                        <i class="bi bi-cup text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Products</h6>
                        <h3 class="mb-0">{{ $activeProducts }}/{{ $totalProducts }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Weekly Sales Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>Weekly Sales
                </h5>
            </div>
            <div class="card-body">
                <canvas id="weeklySalesChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Orders by Status -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-pie-chart me-2"></i>Orders by Status
                </h5>
            </div>
            <div class="card-body">
                <canvas id="ordersStatusChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders & Quick Actions -->
<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Recent Orders
                </h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">
                    View All
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->formatted_total }}</td>
                                <td>
                                    <span class="badge {{ $order->status_badge_class }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No orders yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Product
                    </a>
                    <a href="{{ route('admin.addons.create') }}" class="btn btn-outline-success">
                        <i class="bi bi-plus-circle me-2"></i>Add New Add-on
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-warning">
                        <i class="bi bi-bag me-2"></i>View All Orders
                    </a>
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-info">
                        <i class="bi bi-box-arrow-up-right me-2"></i>View Website
                    </a>
                </div>

                <hr class="my-4">

                <h6 class="mb-3">More Statistics</h6>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Total Orders</span>
                        <span class="badge bg-primary rounded-pill">{{ $totalOrders }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Total Customers</span>
                        <span class="badge bg-success rounded-pill">{{ $totalCustomers }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Monthly Revenue</span>
                        <span class="badge bg-info rounded-pill">${{ number_format($monthRevenue, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Weekly Sales Chart
const weeklySalesCtx = document.getElementById('weeklySalesChart').getContext('2d');
const weeklySalesChart = new Chart(weeklySalesCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(collect($weeklySales)->pluck('day')) !!},
        datasets: [{
            label: 'Sales ($)',
            data: {!! json_encode(collect($weeklySales)->pluck('sales')) !!},
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toFixed(2);
                    }
                }
            }
        }
    }
});

// Orders by Status Chart
const ordersStatusCtx = document.getElementById('ordersStatusChart').getContext('2d');
const ordersStatusChart = new Chart(ordersStatusCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($ordersByStatus)) !!},
        datasets: [{
            data: {!! json_encode(array_values($ordersByStatus)) !!},
            backgroundColor: [
                '#ffc107', // pending - warning
                '#0dcaf0', // confirmed - info
                '#0d6efd', // preparing - primary
                '#198754', // ready - success
                '#6c757d', // completed - secondary
                '#dc3545', // cancelled - danger
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush

@push('styles')
<style>
.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
</style>
@endpush
