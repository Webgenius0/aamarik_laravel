<?php

namespace App\Http\Controllers\Web\Backend\RoleAndPermission;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeRoleManagementController extends Controller
{
    /**
     * Display list of users with their roles
     */
    public function index(Request $request)
    {
        $users = User::with('roles')->where('role','pharmacist')->get();  // Fetch users along with their roles

        return view('backend.layouts.UserRole.index', compact('users'));
    }

    /**
     * Attach a role to a user
     */
    public function attachRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',  // Validate role exists
        ]);

        $user = User::findOrFail($userId);  // Find the user
        $user->assignRole($request->role);  // Attach role

        return response()->json(['success' => true, 'message' => 'Role attached successfully']);
    }

    /**
     * Detach a role from a user
     */
    public function detachRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',  // Validate role exists
        ]);

        $user = User::findOrFail($userId);  // Find the user
        $user->removeRole($request->role);  // Detach role

        return response()->json(['success' => true, 'message' => 'Role detached successfully']);
    }
}
