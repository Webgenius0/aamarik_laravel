<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Department::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button onclick="openDepartmentModal(' . $row->id . ')" class="btn bg-primary text-white">Edit</button>
                            <button onclick="deleteDepartment(' . $row->id . ')" class="btn bg-danger text-white">Delete</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.layouts.Department.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required',
        ]);
        $department = Department::where('department_name', $request->department_name)->first();
        if ($department) {
             return response()->json(['error' => 'Department already exists.']);
        }
        Department::create([
            'department_name' => $request->department_name,
            'status' => 'active',
        ]);
        return response()->json(['message' => 'Department added successfully']);
    }

    public function edit($id)
    {
        return response()->json(Department::find($id));
    }


    public function update(Request $request, $id)
    {
        $department = Department::find($id);
        if ($department) {
            $department->update([
                'department_name' => $request->department_name ?? $department->department_name,
                'status' => $request->status ?? $department->status,
            ]);
        }
        return response()->json(['message' => 'Department updated successfully']);
    }

    public function destroy($id)
    {
        Department::find($id)->delete();
        return response()->json(['message' => 'Department deleted successfully']);
    }
}
