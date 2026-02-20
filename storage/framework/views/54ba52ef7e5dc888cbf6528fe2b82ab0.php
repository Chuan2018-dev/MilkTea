<header class="admin-navbar">
    <div class="d-flex align-items-center justify-content-between w-100">
        <button class="sidebar-toggle btn btn-link text-dark" type="button">
            <i class="bi bi-list fs-4"></i>
        </button>

        <div class="d-flex align-items-center">
            <div class="dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5 me-2"></i>
                    <span class="d-none d-md-inline"><?php echo e(Auth::user()->name); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <span class="dropdown-item-text text-muted">
                            <small><?php echo e(Auth::user()->email); ?></small>
                        </span>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
<?php /**PATH C:\laragon\www\Kimi_Agent\milk-tea-shop\resources\views/partials/admin-navbar.blade.php ENDPATH**/ ?>