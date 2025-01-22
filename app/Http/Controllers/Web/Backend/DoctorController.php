<?php

namespace App\Http\Controllers\Web\Backend;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Exception;
use App\Helper\Helper;
use Yajra\DataTables\Facades\DataTables;
class DoctorController extends Controller
{public function index(Request $request)
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
                    return '<div class="inline-flex gap-1">
                            <a href="javascript:void(0);" onclick="editDoctor(' . $data->id . ')" class="btn bg-success text-white rounded">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="btn bg-danger text-white rounded" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['name','avatar', 'status', 'action'])
                ->make(true); // Ensures the proper JSON response format
        }
    
        return view('backend.layouts.doctor.index');
    }
    
//create-doctor
public function create()
{
    return view('backend.layouts.doctor.create-doctor');
}
    //department 
    public function department()
    {
        return view('backend.layouts.doctor.departament');
    }

    //retribe department
    public function getDeparments()
    {
        $deparment=Department::all();
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

        $user=new User();
        $password='12345678';
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($password);
        $user->role='doctor';
        $user->department=$request->department;
        if ($request->hasFile('avatar')) {
            // Use the helper method to handle file upload and store file path
            $avatarPath = Helper::fileUpload($request->file('avatar'), 'users', 'avatar');
            $user->avatar = $avatarPath; // Assign the avatar path to the user
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
    public function edit($id)
    {
        $doctor = User::findOrFail($id);
        if (!$doctor) {
            return response()->json(['success' => false, 'message' => 'Doctor Not found']);
        }
        return response()->json(['success' => true, 'data' => $doctor]);
    }

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
}
