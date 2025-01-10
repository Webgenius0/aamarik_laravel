<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller
{
    /**
     * list of faq
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Group by type
            $data = FAQ::orderBy('type')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('question', function ($data) {
                    return Str::limit($data->question, 50, '...');
                })
                ->addColumn('answer', function ($data) {
                    return Str::limit($data->answer, 50, '...');
                })
                ->addColumn('status', function ($data) {
                    return '<input type="checkbox" class="form-switch" onclick="ShowStatusChangeAlert(' . $data->id . ')" ' . ($data->status == "active" ? 'checked' : '') . '>';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="inline-flex gap-1">
                        <a href="javascript:void(0);" onclick="editFAQ(' . $data->id . ')" class="btn bg-success text-white rounded">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="btn bg-danger text-white rounded" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>';
                })
                ->rawColumns(['answer','question','status', 'action'])
                ->make(true);
        }
        return view('backend.layouts.faq.index');
    }

    /**
     * Create FAQ
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'question' => 'required|string|max:255|unique:f_a_q_s,question',
            'answer' => 'required|string',
            'type' => 'required|in:Placing an order,Delivery,About myhealthneeds',
        ]);

        $faq = FAQ::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'type' => $request->type,
            'status' => 'Active'
        ]);

        return response()->json(['success' => true, 'message' => 'FAQ created successfully']);
    }

    /**
     * Edit FAQ
     */
    public function edit($id)
    {
        $faq = FAQ::find($id);
        if ($faq) {
            return response()->json(['success' => true, 'data'=>$faq]); // Make sure the FAQ object is returned properly
        }

        return response()->json(['success' => false, 'message' => 'FAQ not found']);
    }

    /**
     * Update FAQ
     */
    public function update(Request $request, $id)
    {
        $faq = FAQ::find($id);
        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'type' => $request->type,
        ]);

        return response()->json(['success' => true, 'message' => 'FAQ updated successfully']);
    }

    /**
     * Update status
     */
    public function updateStatus($id)
    {
        $faq = FAQ::find($id);
        if (!$faq) {
            return response()->json(['success' => false, 'message' => 'Not found']);
        }
        $faq->status = $faq->status == 'active' ? 'inactive' : 'active';
        $faq->save();
        return response()->json(['success' => true, 'message' => 'FAQ Status update successfully']);
    }

    /**
     * Delete FAQ
     */
    public function destroy($id)
    {
        $faq = FAQ::find($id);
        if (!$faq) {
            return response()->json(['success' => false, 'message' => 'FAQ Not found']);
        }
        $faq->delete();

        return response()->json(['success' => true, 'message' => 'FAQ deleted successfully']);
    }
}
