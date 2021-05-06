<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\PrepaidBalanceController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::group(['prefix' => 'prepaid-balance', 'as' => 'prepaid-balance.'], function() {
        Route::get('/', [PrepaidBalanceController::class, 'index'])->name('index');
        Route::post('/store', [PrepaidBalanceController::class, 'store'])->name('store');
    });
    Route::group(['prefix' => 'product', 'as' => 'product.'], function() {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
    });
    Route::group(['as' => 'order.'], function() {
        Route::get('/order', [OrderController::class, 'index'])->name('index');
        Route::get('/success/{id}', [OrderController::class, 'success'])->name('success');
        Route::get('/payment/{id}', [OrderController::class, 'payment'])->name('payment');
        Route::put('/payment/{id}/order', [OrderController::class, 'paymentOrder'])->name('payment.order');
        Route::put('/cancel/{id}/order', [OrderController::class, 'cancelOrder'])->name('cancel.order');
    });
});

