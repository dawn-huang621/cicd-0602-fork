<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::prefix('product')->group(function () {
        Route::get('/index', [ProductController::class, 'index'])->name('product.list');
        Route::get('/create', [ProductController::class, 'create'])->name('product.new');
        Route::get('/store', [ProductController::class, 'store'])->name('product.store');
    });

    Route::prefix('order')->group(function () {
            
        // Route::get('/', function () { return view('order.list');})->name('order');
        Route::get('/order', [ProductController::class, 'list'])->name('order-list');
 
    });

    Route::prefix('customer')->group(function () {
          
        Route::get('/', function () {
            return view('customer.list');
        })->name('customer');
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
