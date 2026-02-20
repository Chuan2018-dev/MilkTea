<?php $__env->startSection('title', 'Checkout'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<section class="checkout-hero text-white py-5">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-2">Checkout</h1>
                <p class="lead mb-0">Complete your details and place your order in just one step.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="<?php echo e(route('cart.index')); ?>" class="btn btn-light btn-lg">
                    <i class="bi bi-arrow-left me-2"></i>Back to Cart
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Checkout -->
<section class="py-5">
    <div class="container">
        <?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <form action="<?php echo e(route('orders.store')); ?>" method="POST" id="checkoutForm">
            <?php echo csrf_field(); ?>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card checkout-card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Delivery Details</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="customer_name" class="form-label">Full Name *</label>
                                    <input
                                        type="text"
                                        id="customer_name"
                                        name="customer_name"
                                        class="form-control <?php $__errorArgs = ['customer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('customer_name', $user->name ?? '')); ?>"
                                        required>
                                    <?php $__errorArgs = ['customer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6">
                                    <label for="customer_phone" class="form-label">Phone Number *</label>
                                    <input
                                        type="text"
                                        id="customer_phone"
                                        name="customer_phone"
                                        class="form-control <?php $__errorArgs = ['customer_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="+63 9xx xxx xxxx"
                                        value="<?php echo e(old('customer_phone', $user->phone ?? '')); ?>"
                                        required>
                                    <?php $__errorArgs = ['customer_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-12">
                                    <label for="delivery_address" class="form-label">Delivery Address *</label>
                                    <textarea
                                        id="delivery_address"
                                        name="delivery_address"
                                        rows="4"
                                        class="form-control <?php $__errorArgs = ['delivery_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="House number, street, barangay, city"
                                        required><?php echo e(old('delivery_address', $user->address ?? '')); ?></textarea>
                                    <?php $__errorArgs = ['delivery_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-12">
                                    <label for="notes" class="form-label">Order Notes (Optional)</label>
                                    <textarea
                                        id="notes"
                                        name="notes"
                                        rows="3"
                                        class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="Any special request for this order"><?php echo e(old('notes')); ?></textarea>
                                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card checkout-card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="bi bi-credit-card-2-front me-2"></i>Payment Method</h5>
                        </div>
                        <div class="card-body p-4">
                            <?php
                                $selectedPayment = old('payment_method', 'cash');
                            ?>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input class="btn-check payment-input" type="radio" name="payment_method" id="payment_cash" value="cash" <?php echo e($selectedPayment === 'cash' ? 'checked' : ''); ?>>
                                    <label class="payment-option" for="payment_cash">
                                        <i class="bi bi-cash-coin"></i>
                                        <strong>Cash</strong>
                                        <span>Pay on delivery</span>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input class="btn-check payment-input" type="radio" name="payment_method" id="payment_card" value="card" <?php echo e($selectedPayment === 'card' ? 'checked' : ''); ?>>
                                    <label class="payment-option" for="payment_card">
                                        <i class="bi bi-credit-card"></i>
                                        <strong>Card</strong>
                                        <span>Debit / Credit card</span>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input class="btn-check payment-input" type="radio" name="payment_method" id="payment_ewallet" value="ewallet" <?php echo e($selectedPayment === 'ewallet' ? 'checked' : ''); ?>>
                                    <label class="payment-option" for="payment_ewallet">
                                        <i class="bi bi-phone"></i>
                                        <strong>E-Wallet</strong>
                                        <span>GCash / Maya</span>
                                    </label>
                                </div>
                            </div>
                            <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small mt-3"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card summary-card border-0 shadow-sm sticky-top">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i>Order Summary</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="summary-items">
                                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $sizeLabel = ucfirst(str_replace('_', ' ', data_get($item, 'size', 'regular')));
                                        $sugarLabel = data_get($item, 'sugar_level', '100%');
                                        $iceLabel = ucfirst(str_replace('_', ' ', data_get($item, 'ice_level', 'regular')));
                                        $addonNames = collect(data_get($item, 'addons', []))
                                            ->map(function ($addon) {
                                                return is_object($addon) ? data_get($addon, 'name') : data_get($addon, 'name');
                                            })
                                            ->filter()
                                            ->implode(', ');
                                    ?>
                                    <div class="summary-item d-flex gap-3">
                                        <img
                                            src="<?php echo e(data_get($item, 'product.image_url')); ?>"
                                            alt="<?php echo e(data_get($item, 'product.name', 'Product')); ?>"
                                            class="summary-item-img"
                                            onerror="this.src='<?php echo e(asset('images/default-product.svg')); ?>'">

                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="mb-1"><?php echo e(data_get($item, 'product.name', 'Product')); ?></h6>
                                                <span class="fw-semibold text-dark">$<?php echo e(number_format((float) data_get($item, 'subtotal', 0), 2)); ?></span>
                                            </div>
                                            <p class="small text-muted mb-1">Qty <?php echo e((int) data_get($item, 'quantity', 1)); ?> | <?php echo e($sizeLabel); ?></p>
                                            <p class="small text-muted mb-0"><?php echo e($sugarLabel); ?> sugar, <?php echo e($iceLabel); ?> ice</p>
                                            <?php if($addonNames): ?>
                                                <p class="small text-muted mb-0">+ <?php echo e($addonNames); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-semibold">$<?php echo e(number_format((float) $subtotal, 2)); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Tax (8%)</span>
                                <span class="fw-semibold">$<?php echo e(number_format((float) $tax, 2)); ?></span>
                            </div>
                            <div class="d-flex justify-content-between total-row mb-4">
                                <span>Total</span>
                                <span>$<?php echo e(number_format((float) $total, 2)); ?></span>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 place-order-btn">
                                <i class="bi bi-bag-check me-2"></i>Place Order
                            </button>
                            <p class="text-muted small text-center mb-0 mt-3">
                                <i class="bi bi-shield-lock me-1"></i>Secure checkout. Your information is protected.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.checkout-hero {
    position: relative;
    overflow: hidden;
    background: linear-gradient(120deg, #0d6efd 0%, #1f8cff 50%, #58b0ff 100%);
}

.checkout-hero::before,
.checkout-hero::after {
    content: '';
    position: absolute;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.16);
}

.checkout-hero::before {
    width: 240px;
    height: 240px;
    top: -110px;
    right: 8%;
}

.checkout-hero::after {
    width: 180px;
    height: 180px;
    bottom: -90px;
    left: 12%;
}

.checkout-card,
.summary-card {
    border-radius: 16px;
}

.summary-card {
    top: 100px;
}

.payment-option {
    border: 1.5px solid #e4e8ef;
    border-radius: 12px;
    padding: 14px;
    background: #fff;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    gap: 4px;
    transition: all 0.2s ease;
    height: 100%;
}

.payment-option i {
    font-size: 1.2rem;
    color: #0d6efd;
}

.payment-option strong {
    font-size: 0.98rem;
    color: #1f2937;
}

.payment-option span {
    font-size: 0.82rem;
    color: #6b7280;
}

.payment-input:checked + .payment-option {
    border-color: #0d6efd;
    box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
    transform: translateY(-2px);
}

.summary-items {
    max-height: 340px;
    overflow: auto;
    padding-right: 4px;
}

.summary-item {
    padding-bottom: 12px;
    margin-bottom: 12px;
    border-bottom: 1px solid #eef1f5;
}

.summary-item:last-child {
    margin-bottom: 0;
    border-bottom: none;
}

.summary-item-img {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    object-fit: cover;
    background: #f4f7fb;
}

.total-row {
    font-size: 1.1rem;
    font-weight: 700;
    color: #111827;
}

.place-order-btn {
    border-radius: 12px;
    padding-top: 0.8rem;
    padding-bottom: 0.8rem;
}

@media (max-width: 991.98px) {
    .summary-card {
        position: static !important;
        top: auto !important;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Kimi_Agent\milk-tea-shop\resources\views\customer\orders\checkout.blade.php ENDPATH**/ ?>