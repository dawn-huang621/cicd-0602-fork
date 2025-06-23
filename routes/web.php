<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::prefix('product')->group(function () {
        Route::get('/', function () {
            return view('product.list');
        })->name('product');
        Route::get('/new', [ProductController::class, 'new'])->name('product-new');
 
    });

    Route::prefix('order')->group(function () {
            
        Route::get('/', function () { return view('order.list');})->name('order');
 
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
