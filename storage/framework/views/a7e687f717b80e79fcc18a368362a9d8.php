<aside class="admin-sidebar">
    <div class="sidebar-header">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-brand">
            <i class="bi bi-cup-straw"></i>
            <span>Admin Panel</span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('admin.dashboard')); ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('admin.orders.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.orders.index')); ?>">
                    <i class="bi bi-bag"></i>
                    <span>Orders</span>
                    <?php
                        $pendingCount = \App\Models\Order::status('pending')->count();
                    ?>
                    <?php if($pendingCount > 0): ?>
                        <span class="badge bg-danger ms-auto"><?php echo e($pendingCount); ?></span>
                    <?php endif; ?>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('admin.products.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.products.index')); ?>">
                    <i class="bi bi-cup"></i>
                    <span>Products</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('admin.addons.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.addons.index')); ?>">
                    <i class="bi bi-plus-circle"></i>
                    <span>Add-ons</span>
                </a>
            </li>

            <li class="nav-item mt-4">
                <span class="nav-section-title">SYSTEM</span>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('home')); ?>" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <span>View Website</span>
                </a>
            </li>

            <li class="nav-item">
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>
<?php /**PATH C:\laragon\www\Kimi_Agent\milk-tea-shop\resources\views/partials/admin-sidebar.blade.php ENDPATH**/ ?>