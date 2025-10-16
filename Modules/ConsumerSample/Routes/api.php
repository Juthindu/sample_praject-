<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\ConsumerSample\Http\Controllers\ConsumerSampleController;
use Modules\ConsumerSample\Http\Controllers\ConsumerSampleDataController;

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

Route::middleware('auth:api')->get('/consumersample', function (Request $request) {
    return $request->user();
});
Route::post('/consumer-sample-final', [ConsumerSampleController::class, 'fetchFinalTableData']);
Route::post('/consumer-samples', [ConsumerSampleController::class, 'fetchTableData']);
Route::post('/consumer-sample-payments', [ConsumerSampleController::class, 'fetchPaymentTableData']);
Route::middleware(['web','auth'])->prefix('admin/consumer-sample')->group(function() {
    Route::post('/store',[ConsumerSampleController::class, 'store'])->name('consumer.samples.store');
    Route::post('/update',[ConsumerSampleController::class, 'update'])->name('consumer.samples.update');
    Route::post('/delete',[ConsumerSampleController::class, 'destroy'])->name('con.sample.delete');
    Route::post('/payment',[ConsumerSampleController::class, 'payment'])->name('consumer.samples.payment');
    Route::post('/consumer-samples/send-mail', [ConsumerSampleController::class, 'sendMail'])->name('consumer.Sample.send.email');
    Route::get('/consumer-samples/{id}/pdf', [ConsumerSampleController::class, 'download'])
     ->name('consumer.samples.pdf');
});

Route::post('/consumer-sample-data-release', [ConsumerSampleDataController::class, 'fetchReleaseTableData']);
Route::post('/consumer-sample-data', [ConsumerSampleDataController::class, 'fetchTableData']);
Route::middleware(['web','auth'])->prefix('admin/consumer-sample-data')->group(function() {
    Route::post('/update',[ConsumerSampleDataController::class, 'update'])->name('consumer.sample.data.test.update');
    Route::post('/confirm',[ConsumerSampleDataController::class, 'confirm'])->name('consumer.sample.data.confirm');
    // Route::get('/export',[ConsumerSampleDataController::class, 'export'])->name('consumer.sample.data.test.export');

});