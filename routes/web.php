<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//controllers
use App\Http\Controllers\DataController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GarantyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\UserController;

//models
use App\Models\{Customer, Seller, Shipment,Product,Invoice};

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
    return Inertia::render('Dashboard',
        [
            'shipments' => Shipment::all(),
            'customers' => Customer::all(),
            'sellers' => Seller::all(),
            'products' => Product::all(),
            'correlative' => Invoice::getLastCorrelative(),
        ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //Data
    Route::resource('/data', DataController::class);

    //user
    Route::resource('/users', UserController::class);
    //updateStatus
    Route::put('/users/{user}/updateStatus', [UserController::class, 'updateStatus'])->name('users.updateStatus');
    //restoreStatus
    Route::put('/users/{user}/restoreStatus', [UserController::class, 'restoreStatus'])->name('users.restoreStatus');

    //sellers
    Route::resource('/sellers', SellerController::class);

    //customers
    Route::resource('/customers', CustomerController::class);

    //products
    Route::resource('/products', ProductController::class);
    //updateSerial
    Route::put('/products/{product}/updateSerial', [ProductController::class, 'updateSerial'])->name('products.updateSerial');
    //camara
    Route::get('/products/camara', [ProductController::class, 'camara'])->name('products.camara');

    //garanty
    Route::resource('/garanty', GarantyController::class);
    //serialUpdate
    Route::post('/garanty/serialUpdate', [GarantyController::class, 'serialUpdate'])->name('garanty.serialUpdate');
    //pdf
    Route::get('/garanty/{garanty}/pdf', [GarantyController::class, 'createPDF'])->name('garanty.pdf');

    //shipment
    Route::resource('/shipments',ShipmentController::class);

    //invoice
    Route::resource('/invoices', InvoiceController::class);
    Route::get('/invoice/{invoice}/pdf', [InvoiceController::class, 'createPDF'])->name('invoices.pdf');
    //garanty
    Route::get('/invoice/{invoice}/garanty', [InvoiceController::class, 'createGaranty'])->name('invoices.garanty');

    Route::get('/search-customer', [InvoiceController::class, 'searchCustomer']);
    Route::get('/search-seller', [InvoiceController::class, 'searchSeller']);
    Route::get('/search-product', [InvoiceController::class, 'searchProduct']);

});

require __DIR__.'/auth.php';
