<?php

namespace App\Http\Controllers\Web\Backend\RoleAndPermission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        // Fetch all permissions
        $permissions = Permission::all();

        return response()->json([
            'success' => true,
            'permissions' => $permissions
        ]);
    }
}
