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

        return view('backend.layouts.dashboard', compact('total_users',));
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
