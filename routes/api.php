<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['web','auth'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['web','auth'])->get('/user-permissions', function (Request $request) {
    return response()->json([
        'roles' => Auth::user()->getRoleNames(), // Get user roles
        'permissions' => Auth::user()->getAllPermissions()->pluck('name') // Get all permissions
    ]);
});

Route::post('/roles', [RoleController::class, 'fetchTableData']);
Route::get('/all/roles', [RoleController::class, 'index'])->name('all.roles');
Route::prefix('admin/role')->group(function() {
    Route::post('/store',[RoleController::class, 'store'])->name('role.store');
    Route::post('/update',[RoleController::class, 'update'])->name('role.update');
    Route::post('/delete',[RoleController::class, 'destroy'])->name('role.delete');
});

Route::resource('permissions', PermissionController::class);