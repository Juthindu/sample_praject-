<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\District\Http\Controllers\DistrictController;

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

Route::middleware('auth:api')->get('/district', function (Request $request) {
    return $request->user();
});

Route::post('/districts', [DistrictController::class, 'fetchTableData']);
Route::middleware(['web','auth'])->prefix('admin/district')->group(function() {
    Route::post('/store',[DistrictController::class, 'store'])->name('district.store');
    Route::post('/update',[DistrictController::class, 'update'])->name('district.update');
    Route::post('/delete',[DistrictController::class, 'destroy'])->name('district.delete');
    Route::get('/export',[DistrictController::class, 'export'])->name('district.export');
    Route::get('/{district}/oic',[DistrictController::class, 'oic'])->name('district.oic');
});