<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::get();
        return response()->json($permissions);
    }

    public function permission()
    {
        return Inertia::render('Modules/Permission/Permission');
    }
}
