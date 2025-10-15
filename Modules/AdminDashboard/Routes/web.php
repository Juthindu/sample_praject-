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
use Modules\AdminDashboard\Http\Controllers\LoginController;

Route::prefix('admin')->group(function() {
    Route::get('/dashboard', [LoginController::class, 'login'])->name('admin.dashboard.login');
});
