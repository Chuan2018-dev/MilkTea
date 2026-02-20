<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Discover the Perfect <span class="text-primary">Milk Tea</span>
                </h1>
                <p class="lead mb-4">
                    Indulge in our handcrafted milk teas, fruit teas, and smoothies made with premium ingredients. 
                    Customize your drink just the way you like it!
                </p>
                <div class="d-flex gap-3">
                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-grid me-2"></i>View Menu
                    </a>
                    <?php if(auth()->guard()->guest()): ?>
                        <a href="<?php echo e(route('register')); ?>" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-person-plus me-2"></i>Join Now
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="<?php echo e(asset('images/hero-milktea.png')); ?>" alt="Milk Tea" class="img-fluid hero-image" 
                     onerror="this.src='https://images.unsplash.com/photo-1558160074-4d7d8bdf4256?w=600'">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Our Categories</h2>
        <div class="row g-4">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-6 col-md-3">
                <a href="<?php echo e(route('products.index', ['category' => $key])); ?>" class="text-decoration-none">
                    <div class="card category-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="category-icon mb-3">
                                <?php if($key == 'milk_tea'): ?>
                                    <i class="bi bi-cup-straw fs-1 text-primary"></i>
                                <?php elseif($key == 'fruit_tea'): ?>
                                    <i class="bi bi-cup fs-1 text-warning"></i>
                                <?php elseif($key == 'smoothie'): ?>
                                    <i class="bi bi-droplet fs-1 text-info"></i>
                                <?php else: ?>
                                    <i class="bi bi-mug fs-1 text-secondary"></i>
                                <?php endif; ?>
                            </div>
                            <h5 class="card-title mb-0"><?php echo e($name); ?></h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="mb-0">Featured Products</h2>
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-primary">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        
        <div class="row g-4">
            <?php $__empty_1 = true; $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card product-card h-100 border-0 shadow-sm">
                    <div class="product-image-wrapper">
                        <img src="<?php echo e($product->image_url); ?>" class="card-img-top" alt="<?php echo e($product->name); ?>"
                             onerror="this.src='https://images.unsplash.com/photo-1558857563-b371033873b8?w=400'">
                        <div class="product-overlay">
                            <a href="<?php echo e(route('products.show', $product)); ?>" class="btn btn-light btn-sm">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="badge bg-secondary mb-2"><?php echo e($product->category_label); ?></span>
                        <h5 class="card-title"><?php echo e($product->name); ?></h5>
                        <p class="card-text text-muted small"><?php echo e(Str::limit($product->description, 50)); ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="h5 mb-0 text-primary"><?php echo e($product->formatted_price); ?></span>
                            <a href="<?php echo e(route('products.show', $product)); ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-cart-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12 text-center">
                <p class="text-muted">No products available yet.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Why Choose Us</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-star-fill fs-1 text-warning"></i>
                    </div>
                    <h4>Premium Quality</h4>
                    <p class="text-muted">We use only the finest tea leaves and fresh ingredients for the best taste.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-sliders fs-1 text-primary"></i>
                    </div>
                    <h4>Fully Customizable</h4>
                    <p class="text-muted">Choose your size, sugar level, ice level, and add-ons to create your perfect drink.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-lightning-fill fs-1 text-success"></i>
                    </div>
                    <h4>Fast Service</h4>
                    <p class="text-muted">Quick preparation and delivery so you can enjoy your drink without the wait.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="mb-3">Ready to Order?</h2>
                <p class="lead text-muted mb-4">
                    Browse our menu and customize your perfect milk tea today!
                </p>
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-grid me-2"></i>Order Now
                </a>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.hero-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 4rem 0;
}

.min-vh-75 {
    min-height: 75vh;
}

.hero-image {
    max-height: 500px;
    filter: drop-shadow(0 20px 40px rgba(0,0,0,0.1));
}

.category-card {
    transition: all 0.3s ease;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
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
    height: 200px;
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

.feature-icon {
    width: 80px;
    height: 80px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Kimi_Agent\milk-tea-shop\resources\views\customer\home.blade.php ENDPATH**/ ?>