<?php

namespace App\Http\Controllers\Web\Backend\MeetingManagement;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MeetingController extends Controller
{
    /**
     * List of meeting
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = Meeting::with('user')->latest()->get();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('meeting_date', function ($data) {
                        return $data->date->format('d-m-Y');
                    })
                    ->addColumn('meeting_time', function ($data) {
                        return $data->time->format('h:i A');
                    })
                    ->addColumn('meeting_link', function ($data) {
                        return '<a href="' . $data->link . '" class="btn btn-round border-gray-50 bg bg-blue-200 text-blue-800">Join Now</a>';
                    })
                    ->addColumn('meeting_user', function ($data) {
                        return $data->user->name ?? 'No Name';
                    })
                    ->addColumn('meeting_status', function ($data) {
                        $statusOptions = ['scheduled', 'completed', 'canceled'];
                        $optionsHtml = '';
                        foreach ($statusOptions as $status) {
                            $selected = $data->status == $status ? 'selected' : '';
                            $optionsHtml .= "<option value='{$status}' {$selected}>{$status}</option>";
                        }
                        return "
                    <select class='form-select' id='status-dropdown-{$data->id}' onchange='updateMeetingStatus({$data->id})'>
                         {$optionsHtml}
                     </select>
                ";
                    })
                    ->addColumn('action', function ($data) {
                        return '<div class="inline-flex gap-1">
                            <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="btn bg-danger text-white rounded" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>';
                    })
                    ->rawColumns(['meeting_date', 'meeting_time', 'meeting_link', 'meeting_user', 'meeting_status', 'action'])
                    ->make(true);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Something went wrong.']);
            }
        }

        return view('backend.layouts.MeetingManagement.index');
    }

    /**
     * update meeting status
     */
    public function updateStatus(Request $request, $id)
    {
        $validate = $request->validate([
            'status' => 'required|in:scheduled,completed,canceled'
        ]);

        $meeting = Meeting::find($id);
        if (!$meeting) {

            return response()->json(['success' => false, 'message' => 'Meeting not found.']);
        }
        $meeting->status = $request->status;
        $meeting->save();

        return response()->json(['success' => true, 'message' => 'Meeting status updated successfully.']);
    }

    /**
     * destroy meeting
     */
    public function destroy($id)
    {
        $meeting = Meeting::find($id);
        if (!$meeting) {

            return response()->json(['success' => false, 'message' => 'Meeting not found.']);
        }

        $meeting->delete();

        return response()->json(['success' => true, 'message' => 'Meeting status delete successfully.']);
    }


}
