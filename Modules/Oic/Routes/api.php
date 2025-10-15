<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Oic\Http\Controllers\OicController;

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

Route::middleware('auth:api')->get('/oic', function (Request $request) {
    return $request->user();
});

Route::post('/oics', [OicController::class, 'fetchTableData']);
Route::middleware(['web','auth'])->prefix('admin/oic')->group(function() {
    Route::post('/store',[OicController::class, 'store'])->name('oic.store');
    Route::post('/update',[OicController::class, 'update'])->name('oic.update');
    Route::post('/delete',[OicController::class, 'destroy'])->name('oic.delete');
    Route::get('/export',[OicController::class, 'export'])->name('oic.export');

});