<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Medicine;
use App\Models\Order;
use App\Models\User;
use App\Models\Verse;
use App\Models\Messaging;
use Carbon\Carbon;
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
        // Count orders created today
        $today_orders = Order::whereDate('created_at', Carbon::today())->count();

        // Count orders created yesterday
        $yesterday_orders = Order::whereDate('created_at', Carbon::yesterday())->count();

        // Calculate the difference between today's and yesterday's orders
        $order_difference = $today_orders - $yesterday_orders;
        //total medicine
        $total_medicines = Medicine::count();

        // Get monthly orders for current and previous year
        $monthly_orders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $previous_monthly_orders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', Carbon::now()->subYear()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');
        //low stock
        $low_stock = Medicine::whereHas('details', function($q) {
            $q->where('stock_quantity', '<=', 10);
        })->with('details')->count();
        return view('backend.layouts.dashboard', compact('monthly_orders','previous_monthly_orders','total_users','today_orders','total_medicines','low_stock','yesterday_orders','order_difference'));
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
