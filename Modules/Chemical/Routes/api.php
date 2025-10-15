<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Chemical\Http\Controllers\AdjustmentChemicalController;
use Modules\Chemical\Http\Controllers\ChemicalController;
use Modules\Chemical\Http\Controllers\StockController;

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

Route::middleware('auth:api')->get('/chemical', function (Request $request) {
    return $request->user();
});
Route::post('/chemicals', [ChemicalController::class, 'fetchTableData']);
Route::middleware(['web','auth'])->prefix('admin/chemical')->group(function() {
    Route::post('/store',[ChemicalController::class, 'store'])->name('chemical.store');
    Route::post('/update',[ChemicalController::class, 'update'])->name('chemical.update');
    Route::post('/delete',[ChemicalController::class, 'destroy'])->name('chemical.delete');
    Route::get('/export',[ChemicalController::class, 'export'])->name('chemical.export');
    Route::get('/search',[ChemicalController::class,'search'])->name('chemical.search');

});

Route::post('/stocks', [StockController::class, 'fetchTableData']);
Route::middleware(['web','auth'])->prefix('admin/stock')->group(function() {
    Route::post('/store',[StockController::class, 'store'])->name('stock.store');
    Route::post('/update',[StockController::class, 'update'])->name('stock.update');
    Route::post('/delete',[StockController::class, 'destroy'])->name('stock.delete');
    Route::get('/export',[StockController::class, 'export'])->name('stock.export');
});

Route::post('/adjustments', [AdjustmentChemicalController::class, 'fetchTableData']);
Route::middleware(['web','auth'])->prefix('admin/adjustment')->group(function() {
    Route::post('/store',[AdjustmentChemicalController::class, 'store'])->name('adjustment.store');
    Route::post('/update',[AdjustmentChemicalController::class, 'update'])->name('adjustment.update');
    Route::post('/delete',[AdjustmentChemicalController::class, 'destroy'])->name('adjustment.delete');
    Route::get('/export',[AdjustmentChemicalController::class, 'export'])->name('adjustment.export');
});