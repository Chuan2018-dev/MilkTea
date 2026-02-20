<?php $__env->startSection('title', 'Order Details'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<section class="page-header bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-2">Order Details</h1>
                <p class="lead mb-0">Order #<?php echo e($order->order_number); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Order Details Section -->
<section class="py-5">
    <div class="container">
        <?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="row">
            <!-- Order Info -->
            <div class="col-lg-8">
                <!-- Status Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <span class="text-muted small d-block">Order Status</span>
                                <span class="badge <?php echo e($order->status_badge_class); ?> fs-6">
                                    <?php echo e($order->status_label); ?>

                                </span>
                            </div>
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <span class="text-muted small d-block">Payment Status</span>
                                <span class="badge <?php echo e($order->payment_status_badge_class); ?> fs-6">
                                    <?php echo e(ucfirst($order->payment_status)); ?>

                                </span>
                            </div>
                            <div class="col-6 col-md-3">
                                <span class="text-muted small d-block">Order Date</span>
                                <span class="fw-bold"><?php echo e($order->created_at->format('M d, Y')); ?></span>
                            </div>
                            <div class="col-6 col-md-3">
                                <span class="text-muted small d-block">Order Time</span>
                                <span class="fw-bold"><?php echo e($order->created_at->format('h:i A')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-cart me-2"></i>Order Items
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                            <div class="row align-items-center">
                                <div class="col-md-2 mb-3 mb-md-0">
                                    <img src="<?php echo e($item->product->image_url); ?>" alt="<?php echo e($item->product_name); ?>"
                                         class="img-fluid rounded" style="height: 80px; object-fit: cover;"
                                         onerror="this.src='https://images.unsplash.com/photo-1558857563-b371033873b8?w=200'">
                                </div>
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <h6 class="mb-1"><?php echo e($item->product_name); ?></h6>
                                    <p class="text-muted mb-0 small">
                                        <span class="badge bg-light text-dark"><?php echo e($item->size_label); ?></span>
                                        <span class="badge bg-light text-dark"><?php echo e($item->sugar_level_label); ?></span>
                                        <span class="badge bg-light text-dark"><?php echo e($item->ice_level_label); ?></span>
                                    </p>
                                    <?php if(!empty($item->addons_list)): ?>
                                    <p class="text-muted mb-0 small mt-1">
                                        <strong>Add-ons:</strong>
                                        <?php echo e(collect($item->addons_list)->pluck('name')->implode(', ')); ?>

                                    </p>
                                    <?php endif; ?>
                                    <?php if($item->special_instructions): ?>
                                    <p class="text-muted mb-0 small mt-1">
                                        <strong>Note:</strong> <?php echo e($item->special_instructions); ?>

                                    </p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-2 text-md-center mb-3 mb-md-0">
                                    <span class="text-muted">x<?php echo e($item->quantity); ?></span>
                                </div>
                                <div class="col-md-2 text-md-end">
                                    <span class="fw-bold text-primary"><?php echo e($item->formatted_subtotal); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-truck me-2"></i>Delivery Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <span class="text-muted small">Customer Name</span>
                                <p class="mb-0 fw-bold"><?php echo e($order->customer_name); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="text-muted small">Phone Number</span>
                                <p class="mb-0 fw-bold"><?php echo e($order->customer_phone); ?></p>
                            </div>
                            <div class="col-12">
                                <span class="text-muted small">Delivery Address</span>
                                <p class="mb-0 fw-bold"><?php echo e($order->delivery_address); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if($order->notes): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-chat-left-text me-2"></i>Order Notes
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0"><?php echo e($order->notes); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-receipt me-2"></i>Order Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span><?php echo e($order->formatted_subtotal); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span><?php echo e($order->formatted_tax); ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5">Total</span>
                            <span class="h5 text-primary"><?php echo e($order->formatted_total); ?></span>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Orders
                            </a>
                            <?php if(in_array($order->status, ['pending', 'confirmed'])): ?>
                            <form action="<?php echo e(route('orders.cancel', $order)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-outline-danger w-100" 
                                        onclick="return confirm('Are you sure you want to cancel this order?')">
                                    <i class="bi bi-x-circle me-2"></i>Cancel Order
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.page-header {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.sticky-top {
    z-index: 100;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Kimi_Agent\milk-tea-shop\resources\views/customer/orders/show.blade.php ENDPATH**/ ?>