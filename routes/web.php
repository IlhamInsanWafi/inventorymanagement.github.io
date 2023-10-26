<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('/suppliers', App\Http\Controllers\SupplierController::class, ['names' => 'supplier']);
    // Customer routes
    Route::resource('/customers', App\Http\Controllers\CustomerController::class, ['names' => 'customer'])->only(['index', 'create', 'edit']);
    // Category routes
    Route::resource('/category', App\Http\Controllers\CategoryController::class, ['names' => 'category']);
    // Product routes
    Route::resource('/product', App\Http\Controllers\ProductController::class, ['names' => 'product']);
    // Unit routes
    Route::resource('/unit', App\Http\Controllers\UnitController::class, ['names' => 'unit']);
    // Purchase routes
    Route::resource('/purchase', App\Http\Controllers\PurchaseController::class, ['names' => 'purchase']);
    Route::get('/category/product/{id}', [App\Http\Controllers\PurchaseController::class, 'getProduct'])->name('product.get');
    // Invoice routes
    Route::resource('/invoice', App\Http\Controllers\InvoiceController::class, ['names' => 'invoice']);
    // Reports routes
    Route::get('/reports/{type}', [App\Http\Controllers\HomeController::class, 'report'])->name('report');
});