<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Consumer\Http\Controllers\ConsumerController;

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

Route::middleware('auth:api')->get('/consumer', function (Request $request) {
    return $request->user();
});
Route::post('/consumers', [ConsumerController::class, 'fetchTableData']);
Route::middleware(['web','auth'])->prefix('admin/consumer')->group(function() {
    Route::post('/store',[ConsumerController::class, 'store'])->name('new.consumer.store');
    Route::post('/update',[ConsumerController::class, 'update'])->name('new.consumer.update');
    Route::post('/delete',[ConsumerController::class, 'destroy'])->name('new.consumer.delete');
    Route::get('/export',[ConsumerController::class, 'export'])->name('new.consumer.export');

});