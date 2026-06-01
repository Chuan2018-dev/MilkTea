<?php

use App\Http\Controllers\Api\MobileApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('mobile')->group(function () {
    Route::get('/catalog', [MobileApiController::class, 'catalog']);
    Route::post('/login', [MobileApiController::class, 'login']);
    Route::post('/register', [MobileApiController::class, 'register']);

    Route::middleware('mobile.auth')->group(function () {
        Route::get('/me', [MobileApiController::class, 'me']);
        Route::patch('/profile', [MobileApiController::class, 'updateProfile']);
        Route::post('/logout', [MobileApiController::class, 'logout']);

        Route::get('/orders', [MobileApiController::class, 'orders']);
        Route::post('/orders', [MobileApiController::class, 'storeOrder']);
        Route::patch('/orders/{order}/status', [MobileApiController::class, 'updateOrderStatus']);
        Route::patch('/orders/{order}/payment', [MobileApiController::class, 'updatePaymentStatus']);

        Route::put('/products/{product}', [MobileApiController::class, 'updateProduct']);
        Route::put('/add-ons/{addOn}', [MobileApiController::class, 'updateAddOn']);
        Route::put('/sizes/{size}', [MobileApiController::class, 'updateSize']);
    });
});
