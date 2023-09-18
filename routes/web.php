<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//controllers
use App\Http\Controllers\DataController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canResetPassword' => Route::has('password.request'),
        'status' => session('status'),
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //Data
    Route::resource('/data', DataController::class);

    //sellers
    Route::resource('/sellers', SellerController::class);

    //customers
    Route::resource('/customers', CustomerController::class);

    //products
    Route::resource('/products', ProductController::class);

    //invoice

    Route::get('/search-customer', [InvoiceController::class, 'searchCustomer']);
    Route::get('/search-seller', [InvoiceController::class, 'searchSeller']);
    Route::get('/search-product', [InvoiceController::class, 'searchProduct']);

});

require __DIR__.'/auth.php';
