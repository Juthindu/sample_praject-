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
use Modules\Chemical\Http\Controllers\AddChemicalController;
use Modules\Chemical\Http\Controllers\AdjustmentChemicalController;
use Modules\Chemical\Http\Controllers\ChemicalController;
use Modules\Chemical\Http\Controllers\StockController;

Route::prefix('admin/chemical')->group(function () {
    Route::post('/data-table', [ChemicalController::class, 'dataTable'])->name('chemical.dataTable');
    Route::get('/', [ChemicalController::class, 'index'])->name('chemical.index');
    Route::get('/create', [ChemicalController::class, 'create'])->name('chemical.create');
    Route::get('/edit/{id}', [ChemicalController::class, 'edit'])->name('chemical.edit');
    Route::get('/add-chemical', [AddChemicalController::class, 'index'])->name('add.chemical.index');
    Route::get('/chemical-adjustment', [AdjustmentChemicalController::class, 'index'])->name('chemical.adjustment.index');
    Route::get('/chemical-stock', [StockController::class, 'index'])->name('chemical.stock.index');
});
