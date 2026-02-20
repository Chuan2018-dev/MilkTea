<?php $__env->startSection('title', 'Our Menu'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<section class="page-header bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-2">Our Menu</h1>
                <p class="lead mb-0">Discover our delicious selection of milk teas, fruit teas, and more!</p>
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="py-5">
    <div class="container">
        <!-- Filters -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <form action="<?php echo e(route('products.index')); ?>" method="GET" class="row g-3">
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
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-2"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle me-2"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row g-4">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card product-card h-100 border-0 shadow-sm">
                    <div class="product-image-wrapper">
                        <img src="<?php echo e($product->image_url); ?>" class="card-img-top" alt="<?php echo e($product->name); ?>"
                             onerror="this.src='https://images.unsplash.com/photo-1558857563-b371033873b8?w=400'">
                        <div class="product-overlay">
                            <a href="<?php echo e(route('products.show', $product)); ?>" class="btn btn-light btn-sm">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="badge bg-secondary mb-2"><?php echo e($product->category_label); ?></span>
                        <h5 class="card-title"><?php echo e($product->name); ?></h5>
                        <p class="card-text text-muted small"><?php echo e(Str::limit($product->description, 60)); ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="h5 mb-0 text-primary"><?php echo e($product->formatted_price); ?></span>
                            <a href="<?php echo e(route('products.show', $product)); ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-cart-plus me-1"></i> Add
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                <h4 class="text-muted">No products found</h4>
                <p class="text-muted">Try adjusting your search or filter criteria.</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            <?php echo e($products->withQueryString()->links()); ?>

        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.page-header {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.product-card {
    transition: all 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.product-image-wrapper {
    position: relative;
    overflow: hidden;
    height: 220px;
}

.product-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image-wrapper img {
    transform: scale(1.05);
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.pagination {
    --bs-pagination-active-bg: #0d6efd;
    --bs-pagination-active-border-color: #0d6efd;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Kimi_Agent\milk-tea-shop\resources\views/customer/products/index.blade.php ENDPATH**/ ?>