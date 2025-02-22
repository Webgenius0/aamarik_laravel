<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Medicine;
use App\Models\Order;
use App\Models\order_item;
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
        // Medicines expiring within the next 5 days or have already expired
        $q->whereBetween('expiry_date', [Carbon::now(), Carbon::now()->addDays(5)]);
    })->orWhereHas('details', function($q) {
        // Medicines already expired (expiry date less than today)
        $q->where('expiry_date', '<=', Carbon::now());
    })->count();


    if (auth()->user()->role == 'admin') {
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
    }else{
        return view('backend.layouts.Employee-dashboard', [
            'orders_by_month' => $orders_by_month,
            'previous_orders_by_month' => $previous_orders_by_month,
            'total_users' => $total_users,
            'today_orders' => $today_orders,
            'total_medicines' => $total_medicines,
            'low_stock' => $low_stock,
            'total_sales' => $total_sales,
            'expire_date_medicines' => $expireDateMedicines
        ]);
    }
}

    /**
     * Selling filtering function
     */
    public function sellingFiltering(Request $request)
    {

        $query = Order::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date . " 00:00:00", $request->end_date . " 23:59:59"]);
        }else{
            $query->whereDate('created_at', Carbon::today());
        }

        $orders = $query->latest('created_at')->get();

        $totalSaleAmount = $orders->sum('sub_total');
        $totalDiscount = $orders->sum('discount');
        $totalShippingCharge = $orders->sum('shipping_charge');
        $totalRoyalMailCharge = $orders->sum('royal_mail_tracked_price');
        $totalTax = $orders->sum('tax');

        // Fetch order items to calculate total buying and selling amounts
        $orderItems = order_item::with('medicine.details')->whereIn('order_id', $orders->pluck('id'))->get();


        $totalBuyingAmount = 0.0;
        $totalSellingAmount = 0.0;
        foreach ($orderItems as $orderItem) {

            $qty = (int) $orderItem->quantity;

            $buyingPrice = is_numeric($orderItem->medicine->details->buying_price) ? (float) $orderItem->medicine->details->buying_price : 0;
            $sellingPrice = is_numeric($orderItem->medicine->details->price) ? (float) $orderItem->medicine->details->price : 0;


            $totalBuyingAmount  += $qty * $buyingPrice;
            $totalSellingAmount += $qty * $sellingPrice;
        }


        $totalProfit = $totalSellingAmount - $totalBuyingAmount;


        return response()->json([
            'orders' => $orders,
            'total_sale_amount' => number_format($totalSaleAmount, 2),
            'total_discount' => number_format($totalDiscount, 2),
            'total_shipping_charge' => number_format($totalShippingCharge, 2),
            'total_royal_mail_charge' => number_format($totalRoyalMailCharge, 2),
            'total_tax' => number_format($totalTax, 2),
            'buying_amount' => number_format($totalBuyingAmount, 2),
            'selling_amount' => number_format($totalSellingAmount, 2),
            'total_profit' => number_format($totalProfit, 2),
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
