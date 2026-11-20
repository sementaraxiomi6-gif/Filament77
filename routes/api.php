
<?php

use App\Http\Controllers\Api\Customer\LoginController;
use App\Http\Controllers\Api\Customer\RegisterController;
use App\Http\Controllers\Api\Customer\LogoutController;
use App\Http\Controllers\Api\Customer\MyOrderController;
use App\Http\Controllers\Api\Customer\MyProfileController;
use App\Http\Controllers\Api\Customer\RatingController;

use App\Http\Controllers\Api\Public\SliderController;
use App\Http\Controllers\Api\Public\CategoryController;
use App\Http\Controllers\Api\Public\ProductController;
use App\Http\Controllers\Api\Public\CartController;
use App\Http\Controllers\Api\Public\RajaOngkirController;
use App\Http\Controllers\Api\Public\CheckoutController;
use App\Http\Controllers\Api\Public\CallbackController;

use Illuminate\Support\Facades\Route;


// ========================================
// ROUTE CUSTOMER
// ========================================

Route::prefix('customer')->group(function () {

    Route::post('/register', RegisterController::class)
        ->name('customer.register');

    Route::post('/login', LoginController::class)
        ->name('customer.login');

    Route::post('/logout', LogoutController::class)
        ->name('customer.logout');

    Route::post('/rating', RatingController::class)
        ->name('customer.rating');

    Route::get('/my-orders', [MyOrderController::class, 'index'])
        ->name('customer.my-orders.index');

    Route::get('/my-orders/{snap_token}', [MyOrderController::class, 'show'])
        ->name('customer.my-orders.show');

    Route::get('/my-profile', [MyProfileController::class, 'index'])
        ->name('customer.my-profile.index');

    Route::post('/my-profile', [MyProfileController::class, 'update'])
        ->name('customer.my-profile.update');

});


// ========================================
// ROUTE SLIDER
// ========================================

Route::get('/sliders', [SliderController::class, 'index'])
    ->name('sliders.index');


// ========================================
// ROUTE CATEGORY
// ========================================

Route::get('/public/categories', [CategoryController::class, 'index'])
    ->name('categories.index');

Route::get('/public/categories/{slug}', [CategoryController::class, 'show'])
    ->name('categories.show');


// ========================================
// ROUTE PRODUCT
// ========================================

Route::get('/public/products', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/public/products-popular', [ProductController::class, 'productPopular'])
    ->name('products.popular');

Route::get('/public/products/{slug}', [ProductController::class, 'show'])
    ->name('products.show');


// ========================================
// ROUTE CART, RAJAONGKIR, CHECKOUT
// ========================================

Route::prefix('public')->group(function () {

    // Cart
    Route::get('/carts', [CartController::class, 'index']);
    Route::post('/carts', [CartController::class, 'store']);

    Route::post('/carts/increment', [CartController::class, 'IncrementCart']);
    Route::post('/carts/decrement', [CartController::class, 'DecrementCart']);

    Route::delete('/carts/{id}', [CartController::class, 'destroy']);


    // RajaOngkir
    Route::get('/search-destination', [RajaOngkirController::class, 'searchDestination']);

    Route::post('/cek-ongkir', [RajaOngkirController::class, 'checkOngkir']);


    // Checkout
    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->middleware('auth:api')
        ->name('checkout.store');

});


// ========================================
// ROUTE API CALLBACK MIDTRANS
// ========================================

// PENTING: Route callback berada DI LUAR group public
Route::post('/callback', CallbackController::class)
    ->name('public.callback');
