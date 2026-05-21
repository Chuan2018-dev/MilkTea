<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Public Routes
Route::get('/', function () {
    return redirect()->route('menu.index');
});

Route::get('/product-images/{product}.svg', [App\Http\Controllers\ProductImageController::class, 'show'])
    ->name('products.generated-image');

// Guest Routes (Auth)
Route::middleware('guest')->group(function () {
    Route::get('register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);

    Route::get('login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [App\Http\Controllers\Auth\NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
        ->name('password.store');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Email Verification
    Route::get('verify-email', App\Http\Controllers\Auth\EmailVerificationPromptController::class)
        ->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', App\Http\Controllers\Auth\VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [App\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('password', [App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');

    // Dashboard route (redirects based on role)
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.dashboard');
    })->name('dashboard');

    // Customer Routes
    Route::middleware('verified')->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Customer\DashboardController::class, 'index'])
            ->name('dashboard');

        // Orders
        Route::get('/orders', [App\Http\Controllers\Customer\OrderController::class, 'index'])
            ->name('orders.index');
        Route::get('/orders/{order}', [App\Http\Controllers\Customer\OrderController::class, 'show'])
            ->name('orders.show');
        Route::post('/orders/{order}/cancel', [App\Http\Controllers\Customer\OrderController::class, 'cancel'])
            ->name('orders.cancel');
    });

    // Menu (accessible by all authenticated users)
    Route::get('/menu', [App\Http\Controllers\Customer\MenuController::class, 'index'])
        ->name('menu.index');
    Route::get('/menu/{product}', [App\Http\Controllers\Customer\MenuController::class, 'show'])
        ->name('menu.show');

    // Cart
    Route::get('/cart', [App\Http\Controllers\Customer\CartController::class, 'index'])
        ->name('cart.index');
    Route::post('/cart', [App\Http\Controllers\Customer\CartController::class, 'store'])
        ->name('cart.store');
    Route::patch('/cart/{key}', [App\Http\Controllers\Customer\CartController::class, 'update'])
        ->name('cart.update');
    Route::delete('/cart/{key}', [App\Http\Controllers\Customer\CartController::class, 'destroy'])
        ->name('cart.destroy');
    Route::delete('/cart', [App\Http\Controllers\Customer\CartController::class, 'clear'])
        ->name('cart.clear');

    // Checkout
    Route::get('/checkout', [App\Http\Controllers\Customer\CheckoutController::class, 'index'])
        ->name('checkout.index');
    Route::post('/checkout', [App\Http\Controllers\Customer\CheckoutController::class, 'store'])
        ->name('checkout.store');

    // Admin Routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Products
        Route::get('/products', [App\Http\Controllers\Admin\ProductController::class, 'index'])
            ->name('products.index');
        Route::get('/products/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])
            ->name('products.create');
        Route::post('/products', [App\Http\Controllers\Admin\ProductController::class, 'store'])
            ->name('products.store');
        Route::get('/products/{product}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])
            ->name('products.edit');
        Route::put('/products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'update'])
            ->name('products.update');
        Route::delete('/products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])
            ->name('products.destroy');

        // Add-ons
        Route::get('/addons', [App\Http\Controllers\Admin\AddOnController::class, 'index'])
            ->name('addons.index');
        Route::get('/addons/create', [App\Http\Controllers\Admin\AddOnController::class, 'create'])
            ->name('addons.create');
        Route::post('/addons', [App\Http\Controllers\Admin\AddOnController::class, 'store'])
            ->name('addons.store');
        Route::get('/addons/{addOn}/edit', [App\Http\Controllers\Admin\AddOnController::class, 'edit'])
            ->name('addons.edit');
        Route::put('/addons/{addOn}', [App\Http\Controllers\Admin\AddOnController::class, 'update'])
            ->name('addons.update');
        Route::delete('/addons/{addOn}', [App\Http\Controllers\Admin\AddOnController::class, 'destroy'])
            ->name('addons.destroy');

        // Sizes
        Route::get('/sizes', [App\Http\Controllers\Admin\SizeController::class, 'index'])
            ->name('sizes.index');
        Route::get('/sizes/create', [App\Http\Controllers\Admin\SizeController::class, 'create'])
            ->name('sizes.create');
        Route::post('/sizes', [App\Http\Controllers\Admin\SizeController::class, 'store'])
            ->name('sizes.store');
        Route::get('/sizes/{size}/edit', [App\Http\Controllers\Admin\SizeController::class, 'edit'])
            ->name('sizes.edit');
        Route::put('/sizes/{size}', [App\Http\Controllers\Admin\SizeController::class, 'update'])
            ->name('sizes.update');
        Route::delete('/sizes/{size}', [App\Http\Controllers\Admin\SizeController::class, 'destroy'])
            ->name('sizes.destroy');

        // Orders
        Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])
            ->name('orders.index');
        Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])
            ->name('orders.show');
        Route::patch('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])
            ->name('orders.status');
        Route::patch('/orders/{order}/payment', [App\Http\Controllers\Admin\OrderController::class, 'updatePayment'])
            ->name('orders.payment');
    });
});
