<?php

//use App\Http\Controllers\AuthController;
//use App\Http\Controllers\RepaymentScheduleController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function(){
    
    
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
        Route::post('/', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/{id}', [CustomerController::class, 'show'])->name('customers.show');
        Route::patch('/{id}', [CustomerController::class, 'update'])->name('customers.update');
    });
    
    
    Route::prefix('stores')->group(function () {
        Route::get('/', [StoreController::class, 'index'])->name('stores.index');
        Route::post('/', [StoreController::class, 'store'])->name('stores.store');
        Route::get('/{id}', [StoreController::class, 'show'])->name('stores.show');
        Route::patch('/{id}', [StoreController::class, 'update'])->name('stores.update');
    });
    
    
    Route::prefix('inventories')->group(function () {
        Route::post('/', [InventoryController::class, 'store'])->name('inventories.store');
        Route::get('/', [InventoryController::class, 'index'])->name('inventories.index');
        Route::get('/{id}', [InventoryController::class, 'show'])->name('inventories.show');
        Route::patch('/{id}', [InventoryController::class, 'update'])->name('inventories.update');
    });
    
    
    Route::prefix('orders')->group(function () {
        Route::post('/', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/report', [OrderController::class, 'report'])->name('orders.report');
        Route::get('/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/{id}', [OrderController::class, 'update'])->name('orders.update');
        
    });

});
