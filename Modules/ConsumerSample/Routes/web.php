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
use Modules\ConsumerSample\Http\Controllers\ConsumerSampleController;
use Modules\ConsumerSample\Http\Controllers\ConsumerSampleDataController;

Route::prefix('admin/consumer-sample')->group(function() {
    Route::post('/data-table',[ConsumerSampleController::class, 'dataTable'])->name('consumer-sample.dataTable');
    Route::get('/',[ConsumerSampleController::class, 'index'])->name('con.sample.index');
    Route::get('/create',[ConsumerSampleController::class, 'create'])->name('con.sample.create');
    Route::get('/edit/{id}',[ConsumerSampleController::class, 'edit'])->name('con.sample.edit');
});

Route::prefix('admin/consumer-sample-data')->group(function() {
    Route::post('/data-table',[ConsumerSampleDataController::class, 'dataTable'])->name('consumer-sample.data.dataTable');
    Route::get('/',[ConsumerSampleDataController::class, 'index'])->name('consumer.sample.data.test.index');
    Route::get('/create',[ConsumerSampleDataController::class, 'create'])->name('consumer.sample.data.test.create');
    Route::get('/release',[ConsumerSampleDataController::class, 'release'])->name('consumer.sample.data.release.index');
    Route::get('/final',[ConsumerSampleDataController::class, 'final'])->name('consumer.sample.data.final.index');
    Route::get('/edit/{id}',[ConsumerSampleDataController::class, 'edit'])->name('consumer.sample.data.test.edit');
    Route::get('/show/{id}',[ConsumerSampleDataController::class, 'show'])->name('consumer.sample.data.release.show');
});

