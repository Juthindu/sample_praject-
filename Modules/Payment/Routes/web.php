<?php

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


use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\PaymentController;

Route::prefix('admin/payment')->group(function () {
    Route::post('/data-table', [PaymentController::class, 'dataTable'])->name('payment.dataTable');
    Route::get('/', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/create', [PaymentController::class, 'create'])->name('payment.create');
    Route::get('/edit/{id}', [PaymentController::class, 'edit'])->name('payment.edit');
});
