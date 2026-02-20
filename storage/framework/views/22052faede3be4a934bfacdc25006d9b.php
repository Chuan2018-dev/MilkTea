<?php $__env->startSection('title', 'Add-ons'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="bi bi-plus-circle me-2"></i>Add-ons
    </h2>
    <a href="<?php echo e(route('admin.addons.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add Add-on
    </a>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="<?php echo e(route('admin.addons.index')); ?>" method="GET" class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" 
                           placeholder="Search add-ons..." value="<?php echo e(request('search')); ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="1" <?php echo e(request('status') === '1' ? 'selected' : ''); ?>>Active</option>
                    <option value="0" <?php echo e(request('status') === '0' ? 'selected' : ''); ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add-ons Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <img src="<?php echo e($addon->image_url); ?>" alt="<?php echo e($addon->name); ?>"
                                 class="rounded" style="width: 50px; height: 50px; object-fit: cover;"
                                 onerror="this.src='https://images.unsplash.com/photo-1558857563-b371033873b8?w=100'">
                        </td>
                        <td>
                            <strong><?php echo e($addon->name); ?></strong>
                        </td>
                        <td><?php echo e($addon->formatted_price); ?></td>
                        <td>
                            <form action="<?php echo e(route('admin.addons.toggle-status', $addon)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-sm <?php echo e($addon->is_active ? 'btn-success' : 'btn-secondary'); ?>">
                                    <?php echo e($addon->is_active ? 'Active' : 'Inactive'); ?>

                                </button>
                            </form>
                        </td>
                        <td><?php echo e($addon->created_at->format('M d, Y')); ?></td>
                        <td>
                            <a href="<?php echo e(route('admin.addons.edit', $addon)); ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?php echo e(route('admin.addons.destroy', $addon)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Are you sure you want to delete this add-on?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted mb-2 d-block"></i>
                            <p class="text-muted mb-0">No add-ons found</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($addons->hasPages()): ?>
    <div class="card-footer bg-white">
        <?php echo e($addons->withQueryString()->links()); ?>

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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Kimi_Agent\milk-tea-shop\resources\views\admin\addons\index.blade.php ENDPATH**/ ?>