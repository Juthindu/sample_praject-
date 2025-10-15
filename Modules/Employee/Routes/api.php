<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Employee\Http\Controllers\EmployeeController;

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

Route::middleware('auth:api')->get('/employee', function (Request $request) {
    return $request->user();
});

Route::post('/employees', [EmployeeController::class, 'fetchTableData']);
Route::middleware(['web','auth'])->prefix('admin/employee')->group(function() {
    Route::post('/store',[EmployeeController::class, 'store'])->name('employee.store');
    Route::post('/update',[EmployeeController::class, 'update'])->name('employee.update');
    Route::post('/delete',[EmployeeController::class, 'destroy'])->name('employee.delete');
    Route::get('/export',[EmployeeController::class, 'export'])->name('employee.export');

});