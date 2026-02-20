<?php $__env->startSection('title', 'My Orders'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<section class="page-header bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-2">My Orders</h1>
                <p class="lead mb-0">View your order history and track current orders</p>
            </div>
        </div>
    </div>
</section>

<!-- Orders Section -->
<section class="py-5">
    <div class="container">
        <?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if($orders->count() > 0): ?>
        <div class="row">
            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 mb-4">
                <div class="card border-0 shadow-sm order-card">
                    <div class="card-header bg-white py-3">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <span class="text-muted small">Order Number</span>
                                <h6 class="mb-0 fw-bold"><?php echo e($order->order_number); ?></h6>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted small">Date</span>
                                <p class="mb-0"><?php echo e($order->created_at->format('M d, Y')); ?></p>
                            </div>
                            <div class="col-md-2">
                                <span class="text-muted small">Total</span>
                                <p class="mb-0 fw-bold text-primary"><?php echo e($order->formatted_total); ?></p>
                            </div>
                            <div class="col-md-2">
                                <span class="text-muted small">Status</span>
                                <span class="badge <?php echo e($order->status_badge_class); ?> d-block w-100">
                                    <?php echo e($order->status_label); ?>

                                </span>
                            </div>
                            <div class="col-md-2 text-md-end">
                                <a href="<?php echo e(route('orders.show', $order)); ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-muted mb-1">
                                    <i class="bi bi-geo-alt me-2"></i><?php echo e(Str::limit($order->delivery_address, 60)); ?>

                                </p>
                                <p class="text-muted mb-0">
                                    <i class="bi bi-credit-card me-2"></i><?php echo e(ucfirst($order->payment_method)); ?>

                                    <span class="badge <?php echo e($order->payment_status_badge_class); ?> ms-2">
                                        <?php echo e(ucfirst($order->payment_status)); ?>

                                    </span>
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <?php if(in_array($order->status, ['pending', 'confirmed'])): ?>
                                <form action="<?php echo e(route('orders.cancel', $order)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                            onclick="return confirm('Are you sure you want to cancel this order?')">
                                        <i class="bi bi-x-circle me-1"></i>Cancel Order
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($orders->links()); ?>

        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-bag-x fs-1 text-muted mb-3"></i>
            <h3 class="text-muted">No orders yet</h3>
            <p class="text-muted mb-4">You haven't placed any orders yet.</p>
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary btn-lg">
                <i class="bi bi-grid me-2"></i>Browse Menu
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.page-header {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.order-card {
    transition: all 0.3s ease;
}

.order-card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.pagination {
    --bs-pagination-active-bg: #0d6efd;
    --bs-pagination-active-border-color: #0d6efd;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Kimi_Agent\milk-tea-shop\resources\views\customer\orders\index.blade.php ENDPATH**/ ?>