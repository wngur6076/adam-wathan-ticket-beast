<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ConcertsController;
use App\Http\Controllers\ConcertsOrdersController;

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

Route::get('/mockups/order', function () {
    return view('orders.show');
});

Route::get('/concerts/{id}', [ConcertsController::class, 'show']);

Route::post('/concerts/{id}/orders', [ConcertsOrdersController::class, 'store']);

Route::get('/orders/{confirmation_number}', [OrdersController::class, 'show']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
