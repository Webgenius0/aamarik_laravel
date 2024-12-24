<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\User;
use App\Models\Verse;
use App\Models\Messaging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\LocationGroup;
use App\Models\LocationReach;

class DashboardController extends Controller
{
    public function index()
    {
        $total_users = User::where('role', 'user')->count();
        $total_locations = Location::where('status', 'active')->count();
        $total_location_groups = LocationGroup::count();
        //today location reach
        $today_location_reach = LocationReach::whereDate('created_at', date('Y-m-d'))->count();
        return view('backend.layouts.dashboard', compact('total_users','total_locations','total_location_groups','today_location_reach'));
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
