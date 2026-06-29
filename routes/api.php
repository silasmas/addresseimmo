<?php

use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\V1\AgencyController;
use App\Http\Controllers\API\V1\AuctionController;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\CartController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\LotteryController;
use App\Http\Controllers\API\V1\ProductController as V1ProductController;
use App\Http\Controllers\API\V1\VerifiedSaleController;
use App\Http\Controllers\API\V1\YaOfeleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 — Frontend Next.js
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->middleware(['localization'])->group(function () {
    Route::get('health', fn () => response()->json([
        'success' => true,
        'message' => 'AddressImmo API v1',
        'data' => ['status' => 'ok'],
    ]));

    Route::get('products', [V1ProductController::class, 'index']);
    Route::get('products/{id}', [V1ProductController::class, 'show'])->whereNumber('id');
    Route::get('categories', [CategoryController::class, 'index']);

    Route::get('agencies', [AgencyController::class, 'index']);
    Route::get('auctions', [AuctionController::class, 'index']);
    Route::get('auctions/{id}', [AuctionController::class, 'show'])->whereNumber('id');
    Route::get('lottery', [LotteryController::class, 'index']);
    Route::get('lottery/{id}', [LotteryController::class, 'show'])->whereNumber('id');
    Route::get('ya-ofele', [YaOfeleController::class, 'index']);
    Route::get('ya-ofele/{id}', [YaOfeleController::class, 'show'])->whereNumber('id');
    Route::get('verified-sales', [VerifiedSaleController::class, 'index']);
    Route::get('verified-sales/{id}', [VerifiedSaleController::class, 'show'])->whereNumber('id');

    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);

    Route::post('payments/webhook', [PaymentController::class, 'store'])->name('api.v1.payments.webhook');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        Route::get('cart', [CartController::class, 'show']);
        Route::post('cart/items', [CartController::class, 'store']);
        Route::patch('cart/items/{orderId}', [CartController::class, 'update'])->whereNumber('orderId');
        Route::delete('cart/items/{orderId}', [CartController::class, 'destroy'])->whereNumber('orderId');
        Route::post('cart/purchase', [CartController::class, 'purchase']);
    });
});

/*
|--------------------------------------------------------------------------
| API legacy — Compatibilité site Blade / FlexPay
|--------------------------------------------------------------------------
*/
Route::middleware(['localization'])->group(function () {
    Route::apiResource('product', ProductController::class);
    Route::apiResource('payment', PaymentController::class);
});

Route::group(['middleware' => ['api', 'localization']], function () {
    Route::resource('product', ProductController::class);
    Route::resource('payment', PaymentController::class);

    Route::post('product/purchase/{cart_id}/{user_id}', [ProductController::class, 'purchase'])
        ->name('product.api.purchase');
    Route::post('payment/store', [PaymentController::class, 'store'])->name('payment.api.store');
    Route::get('payment/find_by_phone/{phone_number}', [PaymentController::class, 'findByPhone'])
        ->name('payment.api.find_by_phone');
    Route::get('payment/find_by_order_number/{order_number}', [PaymentController::class, 'findByOrderNumber'])
        ->name('payment.api.find_by_order_number');
    Route::put('payment/switch_status/{payment_id}/{status_id}', [PaymentController::class, 'switchStatus'])
        ->name('payment.api.switch_status');
});
