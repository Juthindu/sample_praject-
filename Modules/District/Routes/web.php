<?php

use Modules\District\Http\Controllers\DistrictController;
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

Route::prefix('admin/district')->group(function() {
    Route::get('/',[DistrictController::class, 'index'])->name('district.index');
});
Route::prefix('admin/district')->group(function() {
    Route::get('/create',[DistrictController::class, 'create'])->name('district.create');
});
Route::prefix('admin/district')->group(function() {
    Route::get('/edit/{id}',[DistrictController::class, 'edit'])->name('district.edit');
});
