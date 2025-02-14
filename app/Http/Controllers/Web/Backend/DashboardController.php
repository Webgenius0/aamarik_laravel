<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Medicine;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class DashboardController extends Controller
{

public function index(Request $request)
{
    // Get selected month and year from request
    $selectedMonth = $request->input('month', Carbon::now()->month);
    $selectedYear  = $request->input('year', Carbon::now()->year);
    $selectedFilter = $request->input('filter', 'today'); // Default filter is 'today'

    // Total users
    $total_users = User::where('role', 'user')->count();

    // Orders comparison (today vs yesterday)
    $today_orders = Order::whereDate('created_at', Carbon::today())->count();
    $yesterday_orders = Order::whereDate('created_at', Carbon::yesterday())->count();
    $order_difference = $today_orders - $yesterday_orders;

    // Total medicines count
    $total_medicines = Medicine::count();

    // Orders based on selected filter
    $orders_trend = 0;
    $total_sales = 0;

    if (in_array($selectedFilter, ['today', 'this_month', 'this_year', 'last_month'])) {
        $query = Order::query();

        switch ($selectedFilter) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'this_month':
                $query->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $selectedMonth);
                break;
            case 'this_year':
                $query->whereYear('created_at', $selectedYear);
                break;
            case 'last_month':
                $query->whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->subMonth()->month);
                break;
        }

        $orders_trend = $query->count();
        $total_sales = $query->sum('total_price');
    }

    // Monthly orders for charts
    $orders_by_month = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->whereYear('created_at', $selectedYear)
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month');

    $previous_orders_by_month = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->whereYear('created_at', Carbon::now()->subYear()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month');

    // Low stock medicines
    $low_stock = Medicine::whereHas('details', function($q) {
        $q->where('stock_quantity', '<=', 10);
    })->count();

    if ($request->ajax()) {
        return response()->json([
            'orders_trend' => $orders_trend,
            'total_sales' => number_format($total_sales, 2),
        ]);
    }


    //expire date medicines count
    $expireDateMedicines = Medicine::whereHas('details', function($q) {
        $q->whereBetween('expiry_date', [Carbon::now()->subDays(5), Carbon::now()]);
    })->count();


    return view('backend.layouts.dashboard', [
        'orders_by_month' => $orders_by_month,
        'previous_orders_by_month' => $previous_orders_by_month,
        'total_users' => $total_users,
        'today_orders' => $today_orders,
        'total_medicines' => $total_medicines,
        'low_stock' => $low_stock,
        'yesterday_orders' => $yesterday_orders,
        'order_difference' => $order_difference,
        'orders_trend' => $orders_trend,
        'total_sales' => $total_sales,
        'selectedFilter' => $selectedFilter,
        'expire_date_medicines' => $expireDateMedicines
    ]);
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
