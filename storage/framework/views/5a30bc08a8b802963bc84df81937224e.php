<?php $__env->startSection('title', 'Orders'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="bi bi-bag me-2"></i>Orders
    </h2>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="<?php echo e(route('admin.orders.index')); ?>" method="GET" class="row g-3">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" 
                           placeholder="Order number..." value="<?php echo e(request('search')); ?>">
                </div>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request('status') == $key ? 'selected' : ''); ?>>
                            <?php echo e($label); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="payment_status">
                    <option value="">All Payments</option>
                    <option value="pending" <?php echo e(request('payment_status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="paid" <?php echo e(request('payment_status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                    <option value="failed" <?php echo e(request('payment_status') == 'failed' ? 'selected' : ''); ?>>Failed</option>
                    <option value="refunded" <?php echo e(request('payment_status') == 'refunded' ? 'selected' : ''); ?>>Refunded</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date" 
                       value="<?php echo e(request('date')); ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <strong><?php echo e($order->order_number); ?></strong>
                        </td>
                        <td>
                            <?php echo e($order->customer_name); ?>

                            <br>
                            <small class="text-muted"><?php echo e($order->customer_phone); ?></small>
                        </td>
                        <td>
                            <strong class="text-primary"><?php echo e($order->formatted_total); ?></strong>
                        </td>
                        <td>
                            <span class="badge <?php echo e($order->status_badge_class); ?>">
                                <?php echo e($order->status_label); ?>

                            </span>
                        </td>
                        <td>
                            <span class="badge <?php echo e($order->payment_status_badge_class); ?>">
                                <?php echo e(ucfirst($order->payment_status)); ?>

                            </span>
                        </td>
                        <td>
                            <?php echo e($order->created_at->format('M d, Y')); ?>

                            <br>
                            <small class="text-muted"><?php echo e($order->created_at->format('h:i A')); ?></small>
                        </td>
                        <td>
                            <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?php echo e(route('admin.orders.print', $order)); ?>" target="_blank" class="btn btn-sm btn-secondary">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted mb-2 d-block"></i>
                            <p class="text-muted mb-0">No orders found</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($orders->hasPages()): ?>
    <div class="card-footer bg-white">
        <?php echo e($orders->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.pagination {
    --bs-pagination-active-bg: #0d6efd;
    --bs-pagination-active-border-color: #0d6efd;
    margin-bottom: 0;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Kimi_Agent\milk-tea-shop\resources\views\admin\orders\index.blade.php ENDPATH**/ ?>