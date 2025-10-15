<?php

use Illuminate\Support\Facades\Route;
use Modules\Region\Http\Controllers\RegionController;

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

//Please remove when Start backend

Route::prefix('admin/region')->group(function() {
    Route::get('/',[RegionController::class, 'index'])->name('region.index');
});
Route::prefix('admin/region')->group(function() {
    Route::get('/create',[RegionController::class, 'create'])->name('region.create');
});
Route::prefix('admin/region')->group(function() {
    Route::get('/edit/{id}',[RegionController::class, 'edit'])->name('region.edit');
});
