<?php

namespace App\Http\Controllers\Web\Backend;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Exception;
use App\Helper\Helper;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Doctor;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Retrieve doctor records
            $data = User::where('role', 'doctor')->get(); // Fixed with parentheses

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return Str::limit($data->name, 50, '...');
                })
                ->addColumn('status', function ($data) {
                    return '<input type="checkbox" class="form-switch" onclick="ShowStatusChangeAlert(' . $data->id . ')" ' . ($data->status == "active" ? 'checked' : '') . '>';
                })
                ->addColumn('avatar', function ($data) {
                    $avatarUrl = $data->avatar ? asset($data->avatar) : asset('uploads/defult-image/default-avatar.png');
                    return $data->avatar ?
                        '<a href="' . $avatarUrl . '" target="_blank"><img src="' . $avatarUrl . '" alt="Avatar" width="50" height="50"></a>' :
                        'No Avatar';
                })


                ->addColumn('action', function ($data) {
                    $viewRoute = route('doctor.edit', ['doctor' => $data->id]);
                    return '<div class="inline-flex gap-1">
                            <a href="javascript:void(0);" href="' . $viewRoute . '" class="btn bg-success text-white rounded">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="btn bg-danger text-white rounded" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['name', 'avatar', 'status', 'action'])
                ->make(true); // Ensures the proper JSON response format
        }

        return view('backend.layouts.doctor.index');
    }
    //doctor image show 
    // public function show($id)
    // {
    //     $doctor = User::where('type', 'doctor')->first($id);
    //     return view('backend.layouts.doctor.index', compact('doctor'));
    // }

    //doctor Edit

    public function edit(User $doctor)
    {
        Log::info('Editing doctor with ID: ' . $doctor->id);
        return view('backend.layout.doctor.editdoctor', compact('docotor'));
    }

    //create-doctor
    public function create()
    {
        return view('backend.layouts.doctor.create-doctor');
    }
    //create department
    public function departmentCreateForm()
    {
        return view('backend.layouts.doctor.create-department');
    }

    //department list show
    public function department(Request $request)
    {
        if ($request->ajax()) {
            
            $data = Department::all(); 

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return Str::limit($data->department_name, 50, '...');
                })
                ->addColumn('status', function ($data) {
                    return '<input type="checkbox" class="form-switch" onclick="ShowStatusChangeAlert(' . $data->id . ')" ' . ($data->status == "active" ? 'checked' : '') . '>';
                })
                


                ->addColumn('action', function ($data) {
                    $viewRoute = route('doctor.edit', ['doctor' => $data->id]);
                    return '<div class="inline-flex gap-1">
                             <a class="btn btn-sm btn-primary" href="' . $viewRoute . '">
                             <i class="fa-solid fa-pen"></i>
                         </a>
                            <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="btn bg-danger text-white rounded" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['name', 'avatar', 'status', 'action'])
                ->make(true); 
        }

        return view('backend.layouts.doctor.departament');
    }
    //department status update

    public function updateDepartmentStatus($id)
    {
        $dept = Department::find($id);
        if (!$dept) {
            return response()->json(['success' => false, 'message' => 'Not found']);
        }
        $dept->status = $dept->status == 'active' ? 'inactive' : 'active';
        $dept->save();
        return response()->json(['success' => true, 'message' => 'FAQ Status update successfully']);
    }


    //delete -departments
    public function DestroyCategory($id)
    {
        $doctor = Department::find($id);
        if (!$doctor) {
            return response()->json(['success' => false, 'message' => 'Department Not found']);
        }
        $doctor->delete();

        return response()->json(['success' => true, 'message' => 'Department deleted successfully']);
    }


    //retribe department-for doctor updateAnd create
    public function getDeparments()
    {
        $deparment = Department::all();
        return response()->json($deparment);
    }


    //department Store
    public function departmentStore(Request $request)
    {

        $request->validate([
            'department_name' => 'required',
        ]);

        try {

            $department = new Department();
            $department->department_name = $request->department_name;
            $department->status = 'active';
            $department->save();

            return response()->json(['success' => true]);
        } catch (Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    //doctor store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'department' => 'required|exists:departments,department_name',
        ]);
        try {

            $user = new User();
            $password = '12345678';
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($password);
            $user->role = 'doctor';
            $user->department = $request->department;
            if ($request->hasFile('avatar')) {
                
                $avatarPath = Helper::fileUpload($request->file('avatar'), 'users', 'avatar');
                $user->avatar = $avatarPath; 
            }
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Doctor Added Successfully.'
            ], 200);
        } catch (Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //doctor edit
    // public function edit($id)
    // {

    //     $user = User::find($id);

    //     if ($user) {

    //         $avatarUrl = $user->avatar ? asset($user->avatar) : asset('uploads/users/avatar/default-avatar.png');

    //         return response()->json([
    //             'success' => true,
    //             'data' => $user,
    //             'avatar_url' => $avatarUrl
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Doctor not found'
    //     ]);
    // }


    //doctor delete
    public function destroy($id)
    {
        $doctor = User::find($id);
        if (!$doctor) {
            return response()->json(['success' => false, 'message' => 'Doctor Not found']);
        }
        $doctor->delete();

        return response()->json(['success' => true, 'message' => 'Doctor deleted successfully']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'department' => 'required|exists:departments,department_name', // Validating department name
        ]);

        // Find the doctor and update their details
        $doctor = User::findOrFail($id);
        $doctor->name = $request->name;
        $doctor->email = $request->email;

        $department = Department::where('department_name', $request->department)->first();

        if ($department) {
            $doctor->department = $department->department_name;
        }

       
        if ($request->hasFile('avatar')) {
            
            if ($doctor->avatar && file_exists(public_path($doctor->avatar))) {
                unlink(public_path($doctor->avatar));
            }
          
            $avatar = $request->file('avatar');
          
            $uniqueName = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
         
            $path = public_path('uploads/users/avatar');
        
            if (!file_exists($path)) {
                mkdir($path, 0777, true); 
            }
            
            $avatar->move($path, $uniqueName);

            
            $doctor->avatar = 'uploads/users/avatar/' . $uniqueName;
        }

        // Save the doctor's information
        $doctor->save();

        return response()->json([
            'success' => true,
            'message' => 'Doctor updated successfully.',
        ]);
    }
}
