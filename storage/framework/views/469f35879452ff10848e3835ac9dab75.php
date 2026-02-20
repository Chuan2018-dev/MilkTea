<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row">
            <!-- About -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-cup-straw me-2"></i>Milk Tea Shop
                </h5>
                <p class="text-light">
                    Serving the finest milk tea, fruit tea, and smoothies since 2020. 
                    Made with love and the freshest ingredients.
                </p>
                <div class="social-links">
                    <a href="#" class="text-white me-3"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-twitter fs-5"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="<?php echo e(route('home')); ?>" class="text-light text-decoration-none">Home</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo e(route('products.index')); ?>" class="text-light text-decoration-none">Menu</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo e(route('about')); ?>" class="text-light text-decoration-none">About Us</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo e(route('contact')); ?>" class="text-light text-decoration-none">Contact</a>
                    </li>
                </ul>
            </div>

            <!-- Categories -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">Categories</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="<?php echo e(route('products.index', ['category' => 'milk_tea'])); ?>" class="text-light text-decoration-none">Milk Tea</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo e(route('products.index', ['category' => 'fruit_tea'])); ?>" class="text-light text-decoration-none">Fruit Tea</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo e(route('products.index', ['category' => 'smoothie'])); ?>" class="text-light text-decoration-none">Smoothies</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?php echo e(route('products.index', ['category' => 'coffee'])); ?>" class="text-light text-decoration-none">Coffee</a>
                    </li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h6 class="fw-bold mb-3">Contact Us</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-geo-alt me-2"></i>
                        <span class="text-light">123 Tea Street, Milk City, MC 12345</span>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone me-2"></i>
                        <span class="text-light">(555) 123-4567</span>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-envelope me-2"></i>
                        <span class="text-light">hello@milkteashop.com</span>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-clock me-2"></i>
                        <span class="text-light">Mon-Sun: 9AM - 10PM</span>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="my-4">

        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 text-light">
                    &copy; <?php echo e(date('Y')); ?> Milk Tea Shop. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0 text-light">
                    Made with <i class="bi bi-heart-fill text-danger"></i> for tea lovers
                </p>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\laragon\www\Kimi_Agent\milk-tea-shop\resources\views\partials\footer.blade.php ENDPATH**/ ?>