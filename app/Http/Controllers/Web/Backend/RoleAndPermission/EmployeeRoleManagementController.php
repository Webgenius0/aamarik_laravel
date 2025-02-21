<?php

namespace App\Http\Controllers\Web\Backend\RoleAndPermission;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class EmployeeRoleManagementController extends Controller
{
    /**
     * Display list of users with their roles
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = User::with(['roles'])->where('role','pharmacist')->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user_email', fn($data) => $data->email)
                ->addColumn('roles', function ($data) {
                    return $data->roles->map(fn($role) => "<span class='bg-blue-500 text-white m-5 px-2 py-1 rounded text-xs'>
                            {$role->name} <button class='text-red-500 remove-role' data-user='{$data->id}' data-role='{$role->id}'>✖</button></span>")->implode(' ');
                })
                ->addColumn('permissions', function ($data) {
                    // Collect all permissions from roles
                    $rolePermissions = $data->roles->flatMap->permissions->pluck('name')->unique();

                    // Collect all direct permissions assigned to the user
                    $directPermissions = $data->permissions->pluck('name');

                    // Merge role-based and direct permissions and remove duplicates
                    $allPermissions = $rolePermissions->merge($directPermissions)->unique();

                    // Format permissions as HTML badges
                    return "<div id='permissions-{$data->id}'>" .
                            $allPermissions->map(fn($permission) =>
                            "<span class='bg-blue-500 text-white px-2 py-1 rounded text-xs'>{$permission}</span>"
                            )->implode(' ') .
                        "</div>";
                })
                ->addColumn('action', function ($data) {
                    return '<button class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded-md manage-role" data-user="' . $data->id . '">Manage Roles</button>';
                })
                ->rawColumns(['roles', 'permissions', 'action'])
                ->make(true);
        }

        $roles = Role::all();
        return view('backend.layouts.RoleAndPermission.employeeRole.index', compact('roles'));
    }


    /**
     * Attach a role to a user.
     */
    public function attachRole(Request $request, $userId)
    {
        $request->validate(['role' => 'required|exists:roles,id']);

        $user = User::findOrFail($userId);
        $role = Role::findOrFail($request->role);

        if (!$user->hasRole($role->name)) {
            $user->assignRole($role->name);
            return response()->json(['success' => true, 'message' => 'Role attached successfully']);
        }

        return response()->json(['success' => false, 'message' => 'User already has this role']);
    }

    /**
     * Detach a role from a user.
     */
    public function detachRole(Request $request, $userId)
    {
        $request->validate(['role' => 'required|exists:roles,id']);

        $user = User::findOrFail($userId);
        $role = Role::findOrFail($request->role);

        if ($user->hasRole($role->name)) {
            $user->removeRole($role->name);
            return response()->json(['success' => true, 'message' => 'Role detached successfully']);
        }

        return response()->json(['success' => false, 'message' => 'User does not have this role']);
    }
}
