<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:role create', ['only' => ['index', 'getRolePermissions']]);
        // $this->middleware('permission:role view', ['only' => ['store']]);
        // $this->middleware('permission:role update', ['only' => ['givePermissionToRole']]);
        // $this->middleware('permission:role delete', ['only' => ['destroy']]);
    }

    public function roll()
    {
        return Inertia::render('Modules/Permission/Roll');
    }

    public function fetchTableData(Request $request)
    {
        $query = Role::query();

        $pageSize = $request->input('page.pagesize', 10);
        $search = $request->input('page.search', '');
        $sortColumn = $request->input('page.sort_column', 'id');
        $sortDirection = $request->input('page.sort_direction', 'asc');
        $currentPage = $request->input('page.current_page', 1);

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%");
        }

        $query->orderBy($sortColumn, $sortDirection);

        $results = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        return [
            'data' => $results->items(),
            'total' => $results->total(),
            'current_page' => $results->currentPage(),
            'last_page' => $results->lastPage(),
        ];
    }
    public function index()
    {
        $roles = Role::get();
        return response()->json($roles);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
            ]
        ]);

        try{
            $existingRole = Role::where('name', $validated['name'])->exists(); 

            Role::create(['name' => $validated['name']]);
            if(!$existingRole){
                return response()->json([
                    'success' => true,
                    'message' => 'Role Created Successfully.',
                    'redirect' => route('role.management'),
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong while creating the role.',
                ], 500);
            }
         } catch (\Exception $error) {
            Log::error('Role creation failed: ' . $error->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while creating the role.',
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $role_id = $request->id;
        try{
            $model = Role::findOrFail($role_id);
            $model->update(['name' => $request->name]);
            return response()->json([
                'success' => true,
                'message' => 'Role Successfully Updated.',
                'redirect' => route('role.management')]);
        }catch(\Exception $error){
            Log::error('Role update failed: ' . $error->getMessage());
        }
    }

    public function destroy(Request $request)
    {
           try{
            Role::findOrFail($request->id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Role Successfully Deleted.',
                ]);
        }catch(\Exception $error){
            Log::error('Role Delete Failed: ' . $error->getMessage());
        }
    
    }

    public function getRolePermissions($roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissions = $role->permissions->pluck('name');
        return response()->json($permissions);
    }


    public function givePermissionToRole(Request $request, $roleId)
    {
        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $role = Role::findOrFail($roleId);

        $role->syncPermissions($validated['permissions']);

        return response()->json([
            'massage' => 'Permissions successfully assigned to the role.',
        ]);
    }
}
