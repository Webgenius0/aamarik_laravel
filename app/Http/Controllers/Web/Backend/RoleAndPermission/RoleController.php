<?php

namespace App\Http\Controllers\Web\Backend\RoleAndPermission;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display list of role
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            // Group by type
            $data = Role::all();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('permissions', function ($data) {
                    return implode(', ', $data->permissions->pluck('name')->toArray());
                })
                ->addColumn('action', function ($data) {
                    $routeUrl = route('role.edit', $data->id);
                    return '<div class="inline-flex gap-1">
                      <a href="' . $routeUrl . '" class="btn bg-success text-white rounded">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="btn bg-danger text-white rounded" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>';
                })
                ->rawColumns(['permissions','action'])
                ->make(true);
        }
        return view('backend.layouts.RoleAndPermission.Role.index');
    }

    /**
     * create new role with permission
     */
    public function create()
    {
        $permissions = Permission::orderBy('name')->pluck('name', 'id')->toArray();

        return view('backend.layouts.RoleAndPermission.Role.create', compact('permissions'));
    }

    /**
     * store new role with permission
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'role'          => ['required', 'min:4', 'unique:roles,name'],
            'permissions'   => ['required', 'array', 'min:1'],
            'permissions.*' => ['required', 'exists:permissions,id'],
        ]);

        // Fetch permission names based on the permission IDs provided in the request
        $permissionIds = $request->permissions;
        $permissions = Permission::whereIn('id', $permissionIds)->get();

        //create role
        $role = Role::create(['name' => $request->role]);

        // Assign each permission to the role
        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
            }
        }
        return response()->json(['success' => true, 'message' => 'Role created successfully']);
    }

    /**
     * Edit role with role id
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $allPermissions = Permission::all(); // Fetch all permissions
        $rolePermissions = $role->permissions->pluck('id')->toArray(); // Fetch assigned permissions

        return view('backend.layouts.RoleAndPermission.Role.edit', compact('role', 'allPermissions', 'rolePermissions'));
    }


    /**
     * Update role
     */
    public function update(Request $request, $id)
    {

        $validation = $request->validate(['role' => ['required', 'min:4', 'unique:roles,name,' . $id]]);

        $role = Role::findById($id);
        $role->name = $request->role;
        $role->save();


        // Remove all existing permissions before assigning new ones
        $role->permissions()->detach();

        // Fetch permission names based on the permission IDs provided in the request
        $permissionIds = $request->permissions;
        $permissions = Permission::whereIn('id', $permissionIds)->get();

        // Assign each permission to the role
        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
            }
        }

        return response()->json(['success' => true, 'message' => 'Role updated successfully']);
    }

    public function destroy($id)
    {
        $role = Role::findById($id);
        // Remove all existing permissions before assigning new ones
        $role->permissions()->detach();
        $role->delete();
        return response()->json(['success' => true, 'message' => 'Role deleted successfully']);
    }


}
