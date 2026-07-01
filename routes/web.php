<?php

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\PublicController;
use App\Http\Controllers\InstallController;
use Illuminate\Support\Facades\Route;

$adminPanelPath = trim(config('install.admin_panel_path', 'back-office'), '/');

Route::redirect('/admin', "/{$adminPanelPath}");
Route::get('/admin/{path?}', function (?string $path = null) use ($adminPanelPath) {
    $target = '/' . $adminPanelPath;

    if ($path) {
        $target .= '/' . $path;
    }

    return redirect($target);
})->where('path', '.*');

Route::prefix('install')->middleware('not.installed')->name('install.')->group(function () {
    Route::get('/', [InstallController::class, 'index'])->name('index');
    Route::post('/migrate', [InstallController::class, 'migrate'])->name('migrate');
    Route::post('/seed', [InstallController::class, 'seed'])->name('seed');
    Route::post('/admin', [InstallController::class, 'createAdmin'])->name('admin');
    Route::post('/finish', [InstallController::class, 'finish'])->name('finish');
});

Route::middleware('installed')->group(function () {
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::get('/symlink', [PublicController::class, 'symlink'])->name('symlink');
Route::get('/search', [PublicController::class, 'search'])->name('search');
Route::get('/cart', [PublicController::class, 'cart'])->name('cart');
Route::get('/change-lang/{locale}', [PublicController::class, 'changeLanguage'])->name('change_language');
// Products
Route::get('/products', [PublicController::class, 'products'])->name('product.home');
Route::get('/products/{id}', [PublicController::class, 'productDatas'])->whereNumber('id')->name('product.datas');
Route::get('/products/{entity}', [PublicController::class, 'productEntity'])->name('product.entity');
Route::post('/products/{entity}', [PublicController::class, 'addProductEntity']);
Route::post('/products/{entity}/{id}', [PublicController::class, 'updateProductEntity'])->whereNumber('id');
// Payment
Route::get('/pay', [PublicController::class, 'pay'])->name('pay');
Route::post('/pay', [PublicController::class, 'runPay']);
Route::get('/transaction_waiting', [PublicController::class, 'transactionWaiting'])->name('transaction.waiting');
Route::get('/transaction_message/{orderNumber}', [PublicController::class, 'transactionMessage'])->name('transaction.message');
Route::get('/paid/{amount}/{currency}/{code}/{entity}/{entity_id}', [PublicController::class, 'paid'])->whereNumber(['amount', 'code', 'entity_id'])->name('paid');
// Delete something
Route::delete('/delete/{entity}/{id}', [PublicController::class, 'removeData'])->whereNumber('id')->name('data.delete');

Route::middleware('auth')->group(function () {
    Route::get('/change-currency/{currency}', [PublicController::class, 'changeCurrency'])->name('change_currency');

    // Products
    Route::post('/products', [PublicController::class, 'addProduct']);
    // Dashboard legacy → Filament
    Route::redirect('/dashboard', '/' . trim(config('install.admin_panel_path', 'back-office'), '/'))->name('dashboard.home');
    Route::post('/dashboard/role/{entity}/{id}', [AdminController::class, 'updateRoleEntity'])->whereNumber('id');
    // Account
    Route::get('/account', [PublicController::class, 'account'])->name('account.home');
    Route::post('/account', [PublicController::class, 'updateAccount']);
    Route::get('/account/{entity}', [PublicController::class, 'accountEntity'])->name('account.entity');
    Route::get('/account/{entity}/{id}', [PublicController::class, 'accountDatas'])->whereNumber('id')->name('account.entity.datas');
    Route::post('/account/{entity}/{id}', [PublicController::class, 'updateAccountEntity'])->whereNumber('id');
});

require __DIR__ . '/auth.php';
});
