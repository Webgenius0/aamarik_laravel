<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\User;
use App\Models\Verse;
use App\Models\Messaging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {

        $users = User::where('role', '!=', 'admin')->get()->count();
        // $chat = Messaging::where('seen', 0)->with('user')
        //         ->whereIn('id', function ($query) {
        //             $query->select(DB::raw('MAX(id)'))
        //                 ->from('messagings')
        //                 ->where('seen', 0)
        //                 ->groupBy('room_id');
        //         })->count();

        // $verse = Verse::get()->count();

        // return view('backend.layouts.dashboard', compact('users','chat','verse'));

        return view('backend.layouts.dashboard', compact('users'));
    }

     /**
     * Edit Profile
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editProfile()
    {
            return view('backend.layouts.setting.profile');

    }
}
