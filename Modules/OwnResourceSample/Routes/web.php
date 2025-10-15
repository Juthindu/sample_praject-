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
use Modules\OwnResourceSample\Http\Controllers\OwnResourceSampleController;
use Modules\OwnResourceSample\Http\Controllers\OwnSampleController;
use Modules\OwnResourceSample\Http\Controllers\OwnSampleDataController;

Route::prefix('admin/own-resource-sample')->group(function () {
    Route::post('/data-table', [OwnResourceSampleController::class, 'dataTable'])->name('own.resource.sample.dataTable');
    Route::get('/management', [OwnResourceSampleController::class, 'index'])->name('own.resource.sample.index');
     Route::get('/create', [OwnResourceSampleController::class, 'create'])->name('own.resource.sample.create');
});

Route::prefix('admin/own-sample')->group(function() {
    Route::post('/data-table',[OwnSampleController::class, 'dataTable'])->name('own-sample.dataTable');
    Route::get('/',[OwnSampleController::class, 'index'])->name('own.sample.index');
    Route::get('/create',[OwnSampleController::class, 'create'])->name('own.sample.create');
    Route::get('/edit/{id}',[OwnSampleController::class, 'edit'])->name('own.resource.sample.edit');
});


Route::prefix('admin/own-sample-data')->group(function() {
    Route::post('/data-table',[OwnSampleDataController::class, 'dataTable'])->name('own-sample.data.dataTable');
    Route::get('/',[OwnSampleDataController::class, 'index'])->name('own.sample.data.index');
    Route::get('/create',[OwnSampleDataController::class, 'create'])->name('own.sample.data.create');
    Route::get('/release',[OwnSampleDataController::class, 'release'])->name('release.own.sample.data.index');
    Route::get('/final',[OwnSampleDataController::class, 'final'])->name('final.own.sample.data.index');
    Route::get('/edit/{id}',[OwnSampleDataController::class, 'edit'])->name('own.sample.data.edit');
    Route::get('/show/{id}',[OwnSampleDataController::class, 'show'])->name('release.own.sample.data.show');
    Route::get('/final/{id}',[OwnSampleDataController::class, 'finalShow'])->name('own.sample.data.final.show');
});