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

use Modules\Oic\Http\Controllers\OicController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/oic')->group(function() {
    Route::get('/',[OicController::class, 'index'])->name('oic.index');
});
Route::prefix('admin/oic')->group(function() {
    Route::get('/create',[OicController::class, 'create'])->name('oic.create');
});
Route::prefix('admin/oic')->group(function() {
    Route::get('/edit/{id}',[OicController::class, 'edit'])->name('oic.edit');
});
