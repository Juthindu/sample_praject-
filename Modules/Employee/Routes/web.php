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
use Modules\Employee\Http\Controllers\EmployeeController;

Route::prefix('admin/employee')->group(function() {
    Route::post('/data-table',[EmployeeController::class, 'dataTable'])->name('employee.dataTable');
});//Please remove when Start backend

Route::prefix('admin/employee')->group(function() {
    Route::get('/',[EmployeeController::class, 'index'])->name('employee.index');
});
Route::prefix('admin/employee')->group(function() {
    Route::get('/create',[EmployeeController::class, 'create'])->name('employee.create');
});
Route::prefix('admin/employee')->group(function() {
    Route::get('/edit/{id}',[EmployeeController::class, 'edit'])->name('employee.edit');
});
