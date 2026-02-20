<?php $__env->startSection('title', 'Products'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="bi bi-cup me-2"></i>Products
    </h2>
    <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Add Product
    </a>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="<?php echo e(route('admin.products.index')); ?>" method="GET" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="search" 
                           placeholder="Search products..." value="<?php echo e(request('search')); ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="category">
                    <option value="">All Categories</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request('category') == $key ? 'selected' : ''); ?>>
                            <?php echo e($name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="1" <?php echo e(request('status') === '1' ? 'selected' : ''); ?>>Active</option>
                    <option value="0" <?php echo e(request('status') === '0' ? 'selected' : ''); ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>"
                                 class="rounded" style="width: 50px; height: 50px; object-fit: cover;"
                                 onerror="this.src='https://images.unsplash.com/photo-1558857563-b371033873b8?w=100'">
                        </td>
                        <td>
                            <strong><?php echo e($product->name); ?></strong>
                            <br>
                            <small class="text-muted"><?php echo e(Str::limit($product->description, 50)); ?></small>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?php echo e($product->category_label); ?></span>
                        </td>
                        <td><?php echo e($product->formatted_price); ?></td>
                        <td>
                            <form action="<?php echo e(route('admin.products.toggle-status', $product)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-sm <?php echo e($product->is_active ? 'btn-success' : 'btn-secondary'); ?>">
                                    <?php echo e($product->is_active ? 'Active' : 'Inactive'); ?>

                                </button>
                            </form>
                        </td>
                        <td><?php echo e($product->created_at->format('M d, Y')); ?></td>
                        <td>
                            <a href="<?php echo e(route('admin.products.edit', array_merge(['product' => $product], request()->only(['search', 'category', 'status', 'page'])))); ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?php echo e(route('admin.products.destroy', $product)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted mb-2 d-block"></i>
                            <p class="text-muted mb-0">No products found</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($products->hasPages()): ?>
    <div class="card-footer bg-white">
        <?php echo e($products->withQueryString()->links()); ?>

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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Kimi_Agent\milk-tea-shop\resources\views/admin/products/index.blade.php ENDPATH**/ ?>