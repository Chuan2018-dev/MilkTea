<?php $__env->startSection('title', $product->name); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('products.index')); ?>">Menu</a></li>
            <li class="breadcrumb-item active"><?php echo e($product->name); ?></li>
        </ol>
    </div>
</nav>

<!-- Product Details -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Product Image -->
            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow-sm">
                    <img src="<?php echo e($product->image_url); ?>" class="card-img-top" alt="<?php echo e($product->name); ?>"
                         onerror="this.src='https://images.unsplash.com/photo-1558857563-b371033873b8?w=600'"
                         style="height: 400px; object-fit: cover;">
                </div>
            </div>

            <!-- Product Info & Customization -->
            <div class="col-lg-7">
                <span class="badge bg-primary mb-2"><?php echo e($product->category_label); ?></span>
                <h1 class="mb-3"><?php echo e($product->name); ?></h1>
                <p class="text-muted mb-4"><?php echo e($product->description); ?></p>

                <form action="<?php echo e(route('cart.add')); ?>" method="POST" id="orderForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">

                    <!-- Size Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Size</label>
                        <div class="row g-2">
                            <?php $__currentLoopData = $product->available_sizes ?? ['small', 'medium', 'large']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="size" id="size_<?php echo e($size); ?>" 
                                       value="<?php echo e($size); ?>" <?php echo e($size == 'medium' ? 'checked' : ''); ?>>
                                <label class="btn btn-outline-primary w-100" for="size_<?php echo e($size); ?>">
                                    <?php echo e(ucfirst($size)); ?>

                                    <small class="d-block">
                                        $<?php echo e(number_format($product->getPriceForSize($size), 2)); ?>

                                    </small>
                                </label>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Sugar Level -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Sugar Level</label>
                        <div class="row g-2">
                            <?php $__currentLoopData = $product->available_sugar_levels ?? ['0%', '30%', '50%', '70%', '100%']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sugar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col">
                                <input type="radio" class="btn-check" name="sugar_level" id="sugar_<?php echo e(str_replace('%', '', $sugar)); ?>" 
                                       value="<?php echo e($sugar); ?>" <?php echo e($sugar == '100%' ? 'checked' : ''); ?>>
                                <label class="btn btn-outline-secondary w-100" for="sugar_<?php echo e(str_replace('%', '', $sugar)); ?>">
                                    <?php echo e($sugar); ?>

                                </label>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Ice Level -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Ice Level</label>
                        <div class="row g-2">
                            <?php
                            $iceLabels = ['no_ice' => 'No Ice', 'less' => 'Less Ice', 'regular' => 'Regular', 'extra' => 'Extra Ice'];
                            ?>
                            <?php $__currentLoopData = $product->available_ice_levels ?? ['no_ice', 'less', 'regular', 'extra']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-3">
                                <input type="radio" class="btn-check" name="ice_level" id="ice_<?php echo e($ice); ?>" 
                                       value="<?php echo e($ice); ?>" <?php echo e($ice == 'regular' ? 'checked' : ''); ?>>
                                <label class="btn btn-outline-info w-100" for="ice_<?php echo e($ice); ?>">
                                    <?php echo e($iceLabels[$ice] ?? ucfirst($ice)); ?>

                                </label>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Add-ons -->
                    <?php if($addons->count() > 0): ?>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Add-ons</label>
                        <div class="row g-2">
                            <?php $__currentLoopData = $addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6">
                                <div class="form-check border rounded p-3">
                                    <input class="form-check-input addon-checkbox" type="checkbox" 
                                           name="addons[]" value="<?php echo e($addon->id); ?>" 
                                           id="addon_<?php echo e($addon->id); ?>" data-price="<?php echo e($addon->price); ?>">
                                    <label class="form-check-label d-flex justify-content-between w-100" for="addon_<?php echo e($addon->id); ?>">
                                        <span><?php echo e($addon->name); ?></span>
                                        <span class="text-primary">+$<?php echo e(number_format($addon->price, 2)); ?></span>
                                    </label>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Special Instructions -->
                    <div class="mb-4">
                        <label for="special_instructions" class="form-label fw-bold">Special Instructions</label>
                        <textarea class="form-control" id="special_instructions" name="special_instructions" 
                                  rows="2" placeholder="Any special requests?"></textarea>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Quantity</label>
                        <div class="input-group" style="width: 150px;">
                            <button type="button" class="btn btn-outline-secondary" id="decreaseQty">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" class="form-control text-center" name="quantity" id="quantity" 
                                   value="1" min="1" max="10" readonly>
                            <button type="button" class="btn btn-outline-secondary" id="increaseQty">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Price Display -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0">Total Price:</span>
                                <span class="h3 mb-0 text-primary" id="totalPrice">
                                    $<?php echo e(number_format($product->base_price, 2)); ?>

                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Add to Cart Button -->
                    <div class="d-grid gap-2 d-md-flex">
                        <button type="submit" class="btn btn-primary btn-lg flex-fill">
                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                        </button>
                        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>Back to Menu
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Related Products -->
        <?php if($relatedProducts->count() > 0): ?>
        <div class="mt-5 pt-5 border-top">
            <h3 class="mb-4">You May Also Like</h3>
            <div class="row g-4">
                <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-6 col-md-3">
                    <div class="card product-card h-100 border-0 shadow-sm">
                        <div class="product-image-wrapper" style="height: 180px;">
                            <img src="<?php echo e($related->image_url); ?>" class="card-img-top" alt="<?php echo e($related->name); ?>"
                                 onerror="this.src='https://images.unsplash.com/photo-1558857563-b371033873b8?w=400'">
                        </div>
                        <div class="card-body">
                            <h6 class="card-title"><?php echo e($related->name); ?></h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold"><?php echo e($related->formatted_price); ?></span>
                                <a href="<?php echo e(route('products.show', $related)); ?>" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const basePrice = <?php echo e($product->base_price); ?>;
    const sizeMultipliers = {
        'small': 0.85,
        'medium': 1.0,
        'large': 1.25
    };

    function calculateTotal() {
        const size = document.querySelector('input[name="size"]:checked').value;
        const quantity = parseInt(document.getElementById('quantity').value);
        
        let unitPrice = basePrice * sizeMultipliers[size];
        
        // Add addon prices
        document.querySelectorAll('.addon-checkbox:checked').forEach(checkbox => {
            unitPrice += parseFloat(checkbox.dataset.price);
        });
        
        const total = unitPrice * quantity;
        document.getElementById('totalPrice').textContent = '$' + total.toFixed(2);
    }

    // Size change
    document.querySelectorAll('input[name="size"]').forEach(radio => {
        radio.addEventListener('change', calculateTotal);
    });

    // Addon change
    document.querySelectorAll('.addon-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', calculateTotal);
    });

    // Quantity buttons
    document.getElementById('decreaseQty').addEventListener('click', function() {
        const qtyInput = document.getElementById('quantity');
        if (parseInt(qtyInput.value) > 1) {
            qtyInput.value = parseInt(qtyInput.value) - 1;
            calculateTotal();
        }
    });

    document.getElementById('increaseQty').addEventListener('click', function() {
        const qtyInput = document.getElementById('quantity');
        if (parseInt(qtyInput.value) < 10) {
            qtyInput.value = parseInt(qtyInput.value) + 1;
            calculateTotal();
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
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
}

.product-image-wrapper img {
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

.btn-check:checked + .btn-outline-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.btn-check:checked + .btn-outline-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-check:checked + .btn-outline-info {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: white;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Kimi_Agent\milk-tea-shop\resources\views/customer/products/show.blade.php ENDPATH**/ ?>