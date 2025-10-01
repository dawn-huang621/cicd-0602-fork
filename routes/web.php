<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::prefix('product')->group(function () {
        Route::get('/index', [ProductController::class, 'list'])->name('product.list');
        Route::get('/create', [ProductController::class, 'create'])->name('product.new');
        Route::post('/store', [ProductController::class, 'store'])->name('product.store');
    });

    Route::prefix('order')->group(function () {
            
        // Route::get('/', function () { return view('order.list');})->name('order');
        Route::get('/index', [OrderController::class, 'list'])->name('order.list');
        Route::get('/create', [OrderController::class, 'create'])->name('order.new');
        Route::post('/store', [OrderController::class, 'store'])->name('order.store');
        Route::get('/show/{id}', [OrderController::class, 'show'])->name('order.show');
        Route::post('/orders/{id}/review', [OrderController::class, 'appoveOrder'])->name('orders.review');
 
    });

    Route::prefix('customer')->group(function () {
        Route::get('/index', [CustomerController::class, 'list'])->name('customer.list');
        Route::get('/create', [CustomerController::class, 'create'])->name('customer.new');
        Route::post('/store', [CustomerController::class, 'store'])->name('customer.store');
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
