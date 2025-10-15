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
use Modules\Consumer\Http\Controllers\ConsumerController;

Route::prefix('admin/consumer')->group(function() {
    Route::get('/',[ConsumerController::class, 'index'])->name('new.consumer.index');
    Route::get('/create',[ConsumerController::class, 'create'])->name('new.consumer.create');
    Route::get('/edit/{id}',[ConsumerController::class, 'edit'])->name('new.consumer.edit');
});
