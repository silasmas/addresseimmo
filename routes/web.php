<?php
/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::get('/symlink', [PublicController::class, 'symlink'])->name('symlink');
Route::post('/search', [PublicController::class, 'search'])->name('search');
Route::get('/change-lang/{locale}', [PublicController::class, 'changeLanguage'])->name('change_language');
// Products
Route::get('/products', [PublicController::class, 'products'])->name('product.home');
Route::get('/products/{entity}', [PublicController::class, 'productEntity'])->name('product.entity');
Route::post('/products/{entity}', [PublicController::class, 'addProductEntity']);
Route::get('/products/{entity}/{id}', [PublicController::class, 'productDatas'])->whereNumber('id')->name('product.entity.datas');

Route::middleware('auth')->group(function () {
    Route::get('/change-currency/{currency}', [PublicController::class, 'changeCurrency'])->name('change_currency');

    // Products
    Route::post('/products', [PublicController::class, 'addProduct']);
    Route::post('/products/{entity}/{id}', [PublicController::class, 'updateProductEntity'])->whereNumber('id');
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard.home');
    // Role
    Route::get('/dashboard/role', [AdminController::class, 'role'])->name('dashboard.role.home');
    Route::post('/dashboard/role', [AdminController::class, 'addRole']);
    Route::get('/dashboard/role/{id}', [AdminController::class, 'roleDatas'])->whereNumber('id')->name('dashboard.role.datas');
    Route::post('/dashboard/role/{id}', [AdminController::class, 'updateRole'])->whereNumber('id');
    Route::get('/dashboard/role/{entity}', [AdminController::class, 'roleEntity'])->name('dashboard.role.entity.home');
    Route::post('/dashboard/role/{entity}', [AdminController::class, 'addRoleEntity']);
    Route::get('/dashboard/role/{entity}/{id}', [AdminController::class, 'roleEntityDatas'])->whereNumber('id')->name('dashboard.role.entity.datas');
    Route::post('/dashboard/role/{entity}/{id}', [AdminController::class, 'updateRoleEntity'])->whereNumber('id');
    // Category
    Route::get('/dashboard/category', [AdminController::class, 'category'])->name('dashboard.category.home');
    Route::post('/dashboard/category', [AdminController::class, 'addCategory']);
    Route::get('/dashboard/category/{id}', [AdminController::class, 'categoryDatas'])->whereNumber('id')->name('dashboard.category.datas');
    Route::post('/dashboard/category/{id}', [AdminController::class, 'updateCategory'])->whereNumber('id');
    Route::get('/dashboard/category/{entity}', [AdminController::class, 'categoryEntity'])->name('dashboard.category.entity.home');
    Route::post('/dashboard/category/{entity}', [AdminController::class, 'addCategoryEntity']);
    Route::get('/dashboard/category/{entity}/{id}', [AdminController::class, 'categoryEntityDatas'])->whereNumber('id')->name('dashboard.category.entity.datas');
    Route::post('/dashboard/category/{entity}/{id}', [AdminController::class, 'updateCategoryEntity'])->whereNumber('id');
    // Complaints
    Route::get('/dashboard/complaints', [AdminController::class, 'complaints'])->name('dashboard.complaints.home');
    Route::post('/dashboard/complaints', [AdminController::class, 'answerComplaints']);
    Route::get('/dashboard/complaints/{id}', [AdminController::class, 'complaintsDatas'])->whereNumber('id')->name('dashboard.complaints.datas');
    Route::post('/dashboard/complaints/{id}', [AdminController::class, 'updateComplaintsAnswer'])->whereNumber('id');
    // Account
    Route::get('/account', [PublicController::class, 'account'])->name('account.home');
    Route::post('/account', [PublicController::class, 'updateAccount']);
    Route::get('/account/{entity}', [PublicController::class, 'accountEntity'])->name('account.entity');
    Route::get('/account/{entity}/{id}', [PublicController::class, 'accountDatas'])->whereNumber('id')->name('account.entity.datas');
    Route::post('/account/{entity}/{id}', [PublicController::class, 'updateAccountEntity'])->whereNumber('id');
    // Delete something
    Route::delete('/delete/{entity}/{id}', [PublicController::class, 'removeData'])->whereNumber('id')->name('data.delete');
});

require __DIR__ . '/auth.php';
