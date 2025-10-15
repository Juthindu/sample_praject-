<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\OwnResourceSample\Http\Controllers\OwnSampleController;
use Modules\OwnResourceSample\Http\Controllers\OwnSampleDataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/ownresourcesample', function (Request $request) {
    return $request->user();
});

Route::post('/own-sample-final', [OwnSampleController::class, 'fetchFinalTableData']);
Route::post('/own-samples', [OwnSampleController::class, 'fetchTableData']);
Route::post('/own-sample-payments', [OwnSampleController::class, 'fetchPaymentTableData']);
Route::middleware(['web','auth'])->prefix('admin/own-sample')->group(function() {
    Route::post('/store',[OwnSampleController::class, 'store'])->name('own.samples.store');
    Route::post('/update',[OwnSampleController::class, 'update'])->name('own.samples.update');
    Route::post('/delete',[OwnSampleController::class, 'destroy'])->name('own.sample.delete');
    Route::post('/payment',[OwnSampleController::class, 'payment'])->name('own.samples.payment');
    Route::get('/export',[OwnSampleController::class, 'export'])->name('own.samples.export');

});

Route::post('/own-sample-data-release', [OwnSampleDataController::class, 'fetchReleaseTableData']);
Route::post('/own-sample-data', [OwnSampleDataController::class, 'fetchTableData']);
Route::middleware(['web','auth'])->prefix('admin/own-sample-data')->group(function() {
    Route::post('/update',[OwnSampleDataController::class, 'update'])->name('own.sample.data.update');
    Route::post('/confirm',[OwnSampleDataController::class, 'confirm'])->name('own.sample.data.confirm');
    // Route::get('/export',[OwnSampleDataController::class, 'export'])->name('own.sample.data.export');

});