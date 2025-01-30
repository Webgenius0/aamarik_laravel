<?php

namespace App\Http\Controllers\Web\Backend\user;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class EmployeeManagementController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Retrieve doctor records
            $data = User::where('role', 'pharmacist')->get(); // Fixed with parentheses

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return Str::limit($data->name, 50, '...');
                })
                ->addColumn('phone', function ($data) {
                    return $data->phone ?? 'N/A';
                })

                ->addColumn('avatar', function ($data) {
                    $avatarUrl = $data->avatar ? asset($data->avatar) : asset('uploads/defult-image/default-avatar.png');
                    return $data->avatar ?
                        '<a href="' . $avatarUrl . '" target="_blank"><img src="' . $avatarUrl . '" alt="Avatar" width="50" height="50"></a>' :
                        'No Avatar';
                })


                ->addColumn('action', function ($data) {
                    return '<div class="inline-flex gap-1">
                            <a href="javascript:void(0);" onclick="editEmployee(' . $data->id . ')" class="btn bg-success text-white rounded">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="btn bg-danger text-white rounded" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['name','phone', 'avatar', 'status', 'action'])
                ->make(true); // Ensures the proper JSON response format
        }

        return view('backend.layouts.pharmacist.index');
    }

    /**
     * addEmployee
     */
    public function create()
    {
        return view('backend.layouts.pharmacist.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);
        try {

            $user = new User();
            $password = '12345678';
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($password);
            $user->role = 'pharmacist';
            $user->department = $request->department;
            if ($request->hasFile('avatar')) {

                $avatarPath = Helper::fileUpload($request->file('avatar'), 'users', 'avatar');
                $user->avatar = $avatarPath;
            }
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Employee Added Successfully.'
            ], 200);
        } catch (Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    //doctor edit
    public function edit($id)
    {

        $user = User::find($id);

        if ($user) {

            $avatarUrl = $user->avatar ? asset($user->avatar) : asset('uploads/users/avatar/default-avatar.png');

            return response()->json([
                'success' => true,
                'data' => $user,
                'avatar_url' => $avatarUrl
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Employee not found'
        ]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Find the doctor and update their details
        $pharmacist = User::findOrFail($id);
        $pharmacist->name = $request->name ?? $pharmacist->name;
        $pharmacist->email = $request->email ?? $pharmacist->email;
        $pharmacist->phone = $request->phone ?? $pharmacist->phone;



        if ($request->hasFile('avatar')) {

            if ($pharmacist->avatar && file_exists(public_path($pharmacist->avatar))) {
                unlink(public_path($pharmacist->avatar));
            }

            $avatar = $request->file('avatar');

            $uniqueName = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();

            $path = public_path('uploads/users/avatar');

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $avatar->move($path, $uniqueName);


            $pharmacist->avatar = 'uploads/users/avatar/' . $uniqueName;
        }

        // Save the doctor's information
        $pharmacist->save();

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully.',
        ]);
    }

}
