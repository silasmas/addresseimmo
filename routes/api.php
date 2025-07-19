<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['localization'])->group(function () {
    Route::apiResource('product', 'App\Http\Controllers\API\ProductController');
    Route::apiResource('payment', 'App\Http\Controllers\API\PaymentController');
});
/*
|--------------------------------------------------------------------------
| Custom API resource
|--------------------------------------------------------------------------
 */
Route::group(['middleware' => ['api', 'localization']], function () {
    Route::resource('product', 'App\Http\Controllers\API\ProductController');
    Route::resource('payment', 'App\Http\Controllers\API\PaymentController');

    // Product
    Route::post('product/purchase/{cart_id}/{user_id}', 'App\Http\Controllers\API\ProductController@purchase')->name('product.api.purchase');
    // Payment
    Route::post('payment/store', 'App\Http\Controllers\API\PaymentController@store')->name('payment.api.store');
    Route::get('payment/find_by_phone/{phone_number}', 'App\Http\Controllers\API\PaymentController@findByPhone')->name('payment.api.find_by_phone');
    Route::get('payment/find_by_order_number/{order_number}', 'App\Http\Controllers\API\PaymentController@findByOrderNumber')->name('payment.api.find_by_order_number');
    Route::put('payment/switch_status/{payment_id}/{status_id}', 'App\Http\Controllers\API\PaymentController@switchStatus')->name('payment.api.switch_status');
});
