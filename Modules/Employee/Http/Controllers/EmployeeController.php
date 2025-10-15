<?php

namespace Modules\Employee\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;// Remove When start Backend
use Illuminate\Pagination\LengthAwarePaginator; // Remove When start Backend
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Employee\Repositories\Interfaces\EmployeeRepositoryInterface;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function fetchTableData(Request $request)
    {
        return response()->json($this->employeeRepository->all($request));
    }
    public function index()
    {
       return Inertia::render("Modules/Employee/EmployeeManagement");
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $roles = Role::get();
       return Inertia::render("Modules/Employee/CreateEmployee",['data' =>$roles]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'nic' => 'required|string|unique:employees,nic',
            'contact_number' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|string|unique:employees,email',
            'role' => 'required|string', 
            ]);
        try{
            DB::beginTransaction();
             $user = User::create([
                'name' => $validated['first_name'],
                'email' => $validated['email'],
                'password' => Hash::make('defaultPassword123'),
            ]);

            $role = Role::where('name', $validated['role'])->first();
            if ($role) {
                $user->assignRole($role->name); 
            }
            $validated['user_id'] = $user->id;

            $this->employeeRepository->create($validated);
            DB::commit();
            return response()->json([
                    'success' => true,
                    'message' => 'Employee Create Successfully.',
                    'redirect' => route('employee.index'),
                ]);
        }catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('employee::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $roles = Role::get();
        $data = $this->employeeRepository->find($id); 
        $roleId = null;
        if ($data && $data->user_id) {
            $user = User::find($data->user_id);
            if ($user && $user->roles->count() > 0) {
                $roleName = $user->roles->first()->name;
                $role = $roles->firstWhere('name', $roleName);
                $roleId = $role->name ?? null;
            }
        }
        return Inertia::render("Modules/Employee/EditEmployee",['data' => $data, 'roles' => $roles,'roleId' => $roleId]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'nic' => 'required|string|unique:employees,nic,' . $id,
            'contact_number' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|string|unique:employees,email,' . $id,
            'role' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            $employee = $this->employeeRepository->update($id, $validated);
            if ($employee && $employee->user_id) {
            $user = User::find($employee->user_id);

            if ($user) {
                $user->update([
                    'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                    'email' => $validated['email'],
                ]);
                $user->syncRoles([]); 
                $role = Role::where('name', $validated['role'])->first();
                if ($role) {
                    $user->assignRole($role->name);
                }
            }
        }
        DB::commit();
        return response()->json([
            'success' => true,
            'message' => 'Employee Updated Successfully.',
            'redirect' => route('employee.index'),
        ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        try {
            $this->employeeRepository->delete($id);
            return response()->json([
                'success' => true,
                'message' => 'Employee successfully deleted.',
                'redirect' => route('employee.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
